<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use App\Mail\OrderConfirmationMail;
use App\Mail\AdminNewOrderNotification;

class ShopController extends Controller
{
    private const UK_STANDARD_DELIVERY_COST = 4.99;
    private const MAX_ONLINE_PURCHASE_QUANTITY = 1; // Limit to 1 item per product for direct online purchase

    public function index()
    {
        try {
            $products = Product::all();
            return response()->json(['products' => $products]);
        } catch (\Exception $e) {
            Log::error('Error fetching products', [
                'message' => $e->getMessage(),
            ]);
            return response()->json(['message' => 'Failed to load products.'], 500);
        }
    }

    public function createOrder(Request $request)
    {
        $userId = $request->user()?->id;

        try {
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'phone_number' => [
                    'required',
                    'string',
                    'max:255',
                    function ($attribute, $value, $fail) {
                        $digitsOnly = preg_replace('/\D/', '', $value);
                        if (strlen($digitsOnly) < 10) {
                            $fail('Phone number must have at least 10 digits.');
                        } elseif (strlen($digitsOnly) > 11) {
                            $fail('Phone number must have no more than 11 digits.');
                        }
                    }
                ],
                'items' => 'required|array|min:1',
                'items.*.product_id' => 'required|exists:products,id',
                'items.*.quantity' => 'required|integer|min:1|max:' . self::MAX_ONLINE_PURCHASE_QUANTITY,
                'delivery_option' => 'required|in:collection,standard_uk',
                'delivery_address' => [
                    'nullable',
                    'string',
                    'max:1000',
                    function ($attribute, $value, $fail) use ($request) {
                        if ($request->input('delivery_option') === 'standard_uk' && empty($value)) {
                            $fail('Delivery address is required for standard UK delivery.');
                        }
                    }
                ],
                'customer_email' => [
                    'required',
                    'email',
                    'max:255'
                ],
                'street_address' => [
                    'nullable',
                    'string',
                    'max:255',
                    function ($attribute, $value, $fail) use ($request) {
                        if ($request->input('delivery_option') === 'standard_uk' && empty($value)) {
                            $fail('Street address is required for standard UK delivery.');
                        }
                    }
                ],
                'city' => [
                    'nullable',
                    'string',
                    'max:255',
                    function ($attribute, $value, $fail) use ($request) {
                        if ($request->input('delivery_option') === 'standard_uk' && empty($value)) {
                            $fail('City is required for standard UK delivery.');
                        }
                    }
                ],
                'postcode' => [
                    'nullable',
                    'string',
                    'max:20',
                    function ($attribute, $value, $fail) use ($request) {
                        if ($request->input('delivery_option') === 'standard_uk') {
                            if (empty($value)) {
                                $fail('Postcode is required for standard UK delivery.');
                            } else {
                                $postcodeRegex = '/^[A-Z]{1,2}\d[A-Z\d]?\s?\d[A-Z]{2}$/i';
                                if (!preg_match($postcodeRegex, trim($value))) {
                                    $fail('Please enter a valid UK postcode.');
                                }
                            }
                        }
                    }
                ]
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Please check your input and try again.',
                    'errors' => $validator->errors()
                ], 422);
            }

            $firstName = $request->input('first_name');
            $lastName = $request->input('last_name');
            $phoneNumber = $request->input('phone_number');
            $deliveryOption = $request->input('delivery_option');
            $deliveryAddress = $request->input('delivery_address');
            $customerEmail = $request->input('customer_email');
            $selectedItems = $request->input('items');

            // Validate delivery address for UK delivery
            if ($deliveryOption === 'standard_uk' && (empty($deliveryAddress) || trim($deliveryAddress) === '')) {
                return response()->json([
                    'message' => 'Delivery address is required for standard UK delivery.'
                ], 422);
            }

            $totalAmount = 0;
            $deliveryCost = 0;
            $products = [];

            // Calculate total amount and check stock for all items
            foreach ($selectedItems as $item) {
                $product = Product::find($item['product_id']);
                if (!$product) {
                    return response()->json(['message' => 'One or more products not found.'], 404);
                }

                // Check quantity limit
                if ($item['quantity'] > self::MAX_ONLINE_PURCHASE_QUANTITY) {
                    return response()->json([
                        'message' => "You can only purchase " . self::MAX_ONLINE_PURCHASE_QUANTITY . " item of {$product->name} online. Please contact us for larger orders."
                    ], 422);
                }

                // Check stock availability
                if ($product->stock !== null && $product->stock < $item['quantity']) {
                    return response()->json([
                        'message' => "Insufficient stock for {$product->name}. Only {$product->stock} available."
                    ], 422);
                }

                $totalAmount += ($product->price * $item['quantity']);
                $products[] = $product;
            }

            if ($deliveryOption === 'standard_uk') {
                $deliveryCost = self::UK_STANDARD_DELIVERY_COST;
            }

            $totalAmount += $deliveryCost;
            $totalAmountInCents = (int) round($totalAmount * 100);

            // Ensure minimum Stripe amount (50p in GBP)
            if ($totalAmountInCents < 50) {
                return response()->json(['message' => 'Order total is below the minimum required amount.'], 422);
            }

            Stripe::setApiKey(config('services.stripe.secret'));

            try {
                $paymentIntent = PaymentIntent::create([
                    'amount' => $totalAmountInCents,
                    'currency' => 'gbp',
                    'payment_method_types' => ['card'],
                    'metadata' => [
                        'user_id' => (string) $userId,
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                        'phone_number' => $phoneNumber,
                        'delivery_option' => $deliveryOption,
                        'delivery_address' => $deliveryAddress ?? '',
                        'customer_email' => $customerEmail ?? '',
                        'items' => json_encode($selectedItems),
                    ],
                ]);

                // Create order in pending state
                $order = Order::create([
                    'user_id' => $userId,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'phone_number' => $phoneNumber,
                    'delivery_option' => $deliveryOption,
                    'delivery_address' => $deliveryAddress,
                    'delivery_cost' => $deliveryCost,
                    'total_amount' => $totalAmount,
                    'payment_intent_id' => $paymentIntent->id,
                    'payment_status' => 'pending',
                    'order_status' => 'pending',
                    'customer_email' => $customerEmail,
                ]);

                // Create order items and reserve stock
                foreach ($selectedItems as $index => $item) {
                    $product = $products[$index];
                    $order->orderItems()->create([
                        'product_id' => $product->id,
                        'quantity' => $item['quantity'],
                        'price' => $product->price,
                    ]);

                    // Decrement stock if applicable
                    if ($product->stock !== null) {
                        $product->decrement('stock', $item['quantity']);
                    }
                }

                return response()->json([
                    'clientSecret' => $paymentIntent->client_secret,
                    'order_id' => $order->id,
                ]);

            } catch (\Stripe\Exception\ApiErrorException $e) {
                Log::error('Stripe API error', [
                    'message' => $e->getMessage(),
                    'user_id' => $userId,
                    'stripe_error' => $e->getJsonBody()
                ]);
                return response()->json(['message' => 'Payment processing error. Please try again.'], 500);
            }

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Order creation error', [
                'message' => $e->getMessage(),
                'user_id' => $userId,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['message' => 'Failed to create order. Please try again later.'], 500);
        }
    }

    public function confirmOrder(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'payment_intent_id' => 'required|string',
                'order_id' => 'required|exists:orders,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Invalid request data.',
                    'errors' => $validator->errors()
                ], 422);
            }

            $order = Order::with('orderItems')->find($request->input('order_id'));

            if (!$order) {
                return response()->json(['message' => 'Order not found.'], 404);
            }

            if ($order->payment_status === 'succeeded') {
                return response()->json(['message' => 'Order is already confirmed.', 'order' => $order]);
            }

            if ($order->payment_intent_id !== $request->input('payment_intent_id')) {
                Log::warning('Payment intent ID mismatch', [
                    'order_id' => $order->id,
                    'provided_payment_intent' => $request->input('payment_intent_id'),
                    'expected_payment_intent' => $order->payment_intent_id
                ]);
                return response()->json(['message' => 'Invalid payment confirmation.'], 422);
            }

            Stripe::setApiKey(config('services.stripe.secret'));

            try {
                $paymentIntent = PaymentIntent::retrieve($request->input('payment_intent_id'));

                if ($paymentIntent->status === 'succeeded') {
                    $order->update([
                        'payment_status' => 'succeeded',
                        'order_status' => 'confirmed',
                    ]);

                    // Send order confirmation emails with error handling
                    try {
                        if ($order->user) {
                            Mail::to($order->user->email)->send(new OrderConfirmationMail($order));
                        } elseif ($order->customer_email) {
                            Mail::to($order->customer_email)->send(new OrderConfirmationMail($order));
                        }
                    } catch (\Exception $e) {
                        Log::error('Failed to send customer confirmation email', [
                            'order_id' => $order->id,
                            'message' => $e->getMessage()
                        ]);
                    }

                    try {
                        $adminEmail = config('mail.admin_email', env('MAIL_ADMIN_EMAIL'));
                        if ($adminEmail) {
                            Mail::to($adminEmail)->send(new AdminNewOrderNotification($order));
                        }
                    } catch (\Exception $e) {
                        Log::error('Failed to send admin notification email', [
                            'order_id' => $order->id,
                            'message' => $e->getMessage()
                        ]);
                    }

                    return response()->json(['message' => 'Order confirmed successfully.', 'order' => $order]);
                } else {
                    $order->update([
                        'payment_status' => $paymentIntent->status,
                        'order_status' => 'payment_failed',
                    ]);
                    
                    // Restore stock if payment failed
                    foreach ($order->orderItems as $orderItem) {
                        $product = Product::find($orderItem->product_id);
                        if ($product && $product->stock !== null) {
                            $product->increment('stock', $orderItem->quantity);
                        }
                    }
                    
                    return response()->json([
                        'message' => 'Payment not completed.',
                        'payment_status' => $paymentIntent->status
                    ], 402);
                }

            } catch (\Stripe\Exception\ApiErrorException $e) {
                Log::error('Stripe payment confirmation error', [
                    'message' => $e->getMessage(),
                    'payment_intent_id' => $request->input('payment_intent_id'),
                    'stripe_error' => $e->getJsonBody()
                ]);
                return response()->json(['message' => 'Payment verification failed.'], 500);
            }

        } catch (\Exception $e) {
            Log::error('Order confirmation error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['message' => 'Failed to confirm order.'], 500);
        }
    }
}
