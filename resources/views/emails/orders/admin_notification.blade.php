```blade
@component('mail::message')
# New Order Received

A new order has been placed on your website.

**Order Details:**
- **Order ID:** {{ $order->id }}
- **Customer Name:** @if($order->user){{ $order->user->name }}@else Guest @endif
- **Customer Email:** @if($order->user){{ $order->user->email }}@else{{ $order->customer_email }}@endif
- **Total Amount:** £{{ number_format($order->total_amount, 2) }}
- **Delivery Option:** {{ ucfirst(str_replace('_', ' ', $order->delivery_option)) }}
@if($order->delivery_address)
- **Delivery Address:** {{ $order->delivery_address }}
@endif
- **Payment Intent ID:** {{ $order->payment_intent_id }}
- **Payment Status:** {{ ucfirst($order->payment_status) }}

**Items:**
@component('mail::table')
| Product       | Quantity    | Price         |
|:--------------|:------------|:--------------|
@foreach ($order->orderItems as $item)
| {{ $item->product->name }} | {{ $item->quantity }} | £{{ number_format($item->price, 2) }} |
@endforeach
@endcomponent

Please prepare and dispatch the journal/book.

Thanks,
{{ config('app.name') }}
@endcomponent
```