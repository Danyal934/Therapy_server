```blade
@component('mail::message')
# Order Confirmation

Thank you for your order, @if($order->user){{ $order->user->name }}@else{{ $order->customer_email }}@endif!

Your order #{{ $order->id }} has been confirmed and is being processed.

**Order Details:**
- **Total Amount:** £{{ number_format($order->total_amount, 2) }}
- **Delivery Option:** {{ ucfirst(str_replace('_', ' ', $order->delivery_option)) }}
@if($order->delivery_address)
- **Delivery Address:** {{ $order->delivery_address }}
@endif

**Items:**
@component('mail::table')
| Product       | Quantity    | Price         |
|:--------------|:------------|:--------------|
@foreach ($order->orderItems as $item)
| {{ $item->product->name }} | {{ $item->quantity }} | £{{ number_format($item->price, 2) }} |
@endforeach
@endcomponent

We will notify you once your order has been dispatched.

Thanks,
{{ config('app.name') }}
@endcomponent
```