@extends('layouts.app')

@section('title', 'Shop - LCI Wellbeing')

@section('content')
<style>
    .lci-shop-container { max-width: 1400px; margin: 0 auto; padding: 1.5rem 1rem; }
    @media (min-width: 768px) { .lci-shop-container { padding-top: 2.5rem; padding-bottom: 2.5rem; } }
    .lci-checkout-grid { display: grid; grid-template-columns: 1fr; gap: 2.5rem; margin-bottom: 4rem; }
    @media (min-width: 1024px) { .lci-checkout-grid { grid-template-columns: 2fr 1fr; gap: 3rem; align-items: start; } }
    .lci-summary-col { min-width: 0; }
    .lci-summary-col > div:first-child { position: sticky; top: 1.5rem; }
    .form-input-invalid { border-color: #ef4444 !important; border-width: 2px; }
    .error-text { 
        color: #ef4444; 
        font-size: 0.75rem; 
        margin-top: 0.5rem; 
        display: block; 
        font-weight: 500;
    }
</style>
<div class="container mx-auto px-4 py-6 md:py-10 lci-shop-container">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 lg:gap-12 mb-16 lci-checkout-grid">
        <!-- Left Column - Checkout Form -->
        <div class="lg:col-span-2">
            <div class="mb-10">
                <h1 class="font-serif text-4xl md:text-5xl font-semibold text-ink mb-3" style="color:#B8860B;">Finalize Order</h1>
                <p class="text-ink-soft text-base" style="color:#6b645c;">Complete your purchase of the LCI Wellbeing Journal to begin your journey towards mindful living and intellectual depth.</p>
            </div>

            <!-- Contact Information -->
            <div class="mb-10">
                <div class="flex items-center gap-2 mb-6 pb-4 border-b border-hairline" style="border-bottom:1px solid #E2D9C9; margin-top:0.5rem; margin-bottom:1.25rem; padding-bottom:1rem;">
                    <svg class="w-5 h-5 text-gold" style="color:#B8860B;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    <h2 class="font-serif font-semibold text-lg text-ink" style="color:#B8860B;">Contact Information</h2>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4" style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-top:0.5rem;">
                    <div class="space-y-2">
                        <label class="text-xs font-medium text-ink-soft uppercase tracking-wider" style="color:#6b645c;">First Name</label>
                        <input type="text" id="first_name" class="w-full px-4 py-3 border border-hairline rounded-lg text-ink text-sm" style="border:1px solid #E2D9C9; border-radius:0.5rem;" placeholder="e.g. Eleanor" />
                        <span id="first_name_error" class="error-text"></span>
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-medium text-ink-soft uppercase tracking-wider" style="color:#6b645c;">Last Name</label>
                        <input type="text" id="last_name" class="w-full px-4 py-3 border border-hairline rounded-lg text-ink text-sm" style="border:1px solid #E2D9C9; border-radius:0.5rem;" placeholder="e.g. Thorne" />
                        <span id="last_name_error" class="error-text"></span>
                    </div>
                </div>

                <div class="mt-4 mb-4">
                    <label class="text-xs font-medium text-ink-soft uppercase tracking-wider block mb-2" style="color:#6b645c;">Email Address</label>
                    <input type="email" id="customer_email" class="w-full px-4 py-3 border border-hairline rounded-lg text-ink text-sm" style="border:1px solid #E2D9C9; border-radius:0.5rem;" placeholder="eleanor@wellbeing.com" />
                    <span id="customer_email_error" class="error-text"></span>
                </div>

                <div class="mt-4 mb-4">
                    <label class="text-xs font-medium text-ink-soft uppercase tracking-wider block mb-2" style="color:#6b645c;">Phone Number</label>
                    <input type="tel" id="phone_number" class="w-full px-4 py-3 border border-hairline rounded-lg text-ink text-sm" style="border:1px solid #E2D9C9; border-radius:0.5rem;" placeholder="+44 7000 000000" />
                    <span id="phone_number_error" class="error-text"></span>
                </div>
            </div>

            <!-- Shipping Address -->
            <div class="mb-10">
                <div class="flex items-center gap-2 mb-6 pb-4 border-b border-hairline" style="border-bottom:1px solid #E2D9C9; margin-top:0.5rem; margin-bottom:1.25rem; padding-bottom:1rem;">
                    <svg class="w-5 h-5 text-gold" style="color:#B8860B;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16V4a1 1 0 011-1h6a1 1 0 011 1v12H8zM8 16H4a1 1 0 01-1-1V9a1 1 0 011-1h4v8zm8 0h4.586a1 1 0 00.707-.293l1.414-1.414a1 1 0 00.293-.707V11a1 1 0 00-.293-.707l-2.414-2.414A1 1 0 0019.586 7H16v9zm-8 0a2 2 0 104 0m6 0a2 2 0 104 0"></path>
                    </svg>
                    <h2 class="font-serif font-semibold text-lg text-ink" style="color:#B8860B;">Shipping Address</h2>
                </div>

                <div class="mb-4">
                    <label class="text-xs font-medium text-ink-soft uppercase tracking-wider block mb-2" style="color:#6b645c;">Street Address</label>
                    <input type="text" id="street_address" class="w-full px-4 py-3 border border-hairline rounded-lg text-ink text-sm" style="border:1px solid #E2D9C9; border-radius:0.5rem;" placeholder="Flat, House Name, Street Name" />
                    <span id="street_address_error" class="error-text"></span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 mt-4" style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                    <div class="space-y-2">
                        <label class="text-xs font-medium text-ink-soft uppercase tracking-wider" style="color:#6b645c;">City</label>
                        <input type="text" id="city" class="w-full px-4 py-3 border border-hairline rounded-lg text-ink text-sm" style="border:1px solid #E2D9C9; border-radius:0.5rem;" placeholder="London" />
                        <span id="city_error" class="error-text"></span>
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-medium text-ink-soft uppercase tracking-wider" style="color:#6b645c;">Postcode</label>
                        <input type="text" id="postcode" class="w-full px-4 py-3 border border-hairline rounded-lg text-ink text-sm" style="border:1px solid #E2D9C9; border-radius:0.5rem;" placeholder="SW1A 1AA" />
                        <span id="postcode_error" class="error-text"></span>
                    </div>
                </div>

                <!-- Delivery Method -->
                <div class="pt-4 border-t border-hairline" style="border-top:1px solid #E2D9C9; padding-top:1.5rem; margin-top:1rem;">
                    <label class="text-xs font-medium text-ink-soft uppercase tracking-wider block mb-4" style="color:#6b645c;">Delivery Method</label>
                    <div class="flex flex-col md:flex-row gap-3">
                        <label class="flex-1 flex items-center justify-between p-4 border-2 border-hairline rounded-lg cursor-pointer transition-all hover:border-gold delivery-option" data-value="standard_uk" data-cost="4.99" style="border:2px solid #B8860B; border-radius:0.5rem; background-color:#F7EEE0;">
                            <div class="flex items-center">
                                <input type="radio" name="delivery_option" value="standard_uk" checked class="h-4 w-4 text-gold" style="margin-right: 1rem;" />
                                <div class="text-sm">
                                    <span class="block font-medium text-ink" style="color:#2B2620;">Standard UK Delivery</span>
                                    <span class="text-xs text-ink-soft" style="color:#6b645c;">3-5 Working Days</span>
                                </div>
                            </div>
                            <span class="text-sm font-medium text-ink" style="color:#2B2620;">£4.99</span>
                        </label>
                        <label class="flex-1 flex items-center justify-between p-4 border-2 border-hairline rounded-lg cursor-pointer transition-all hover:border-gold delivery-option" data-value="collection" data-cost="0" style="border:2px solid #E2D9C9; border-radius:0.5rem;">
                            <div class="flex items-center">
                                <input type="radio" name="delivery_option" value="collection" class="h-4 w-4 text-gold" style="margin-right: 1rem;" />
                                <div class="text-sm">
                                    <span class="block font-medium text-ink" style="color:#2B2620;">Collection</span>
                                    <span class="text-xs text-ink-soft" style="color:#6b645c;">From London HQ</span>
                                </div>
                            </div>
                            <span class="text-sm font-medium text-ink" style="color:#2B2620;">Free</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Payment Section -->
            <div class="mb-10">
                <div class="flex items-center gap-2 mb-6 pb-4 border-b border-hairline" style="border-bottom:1px solid #E2D9C9; margin-top:0.5rem; margin-bottom:1.25rem; padding-bottom:1rem;">
                    <svg class="w-5 h-5 text-gold" style="color:#B8860B;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                    <h2 class="font-serif font-semibold text-lg text-ink" style="color:#B8860B;">Payment Securely</h2>
                </div>

                <form id="payment-form" class="bg-cream p-6 rounded-2xl" style="background-color:#F7EEE0; border-radius:1rem; padding:1.5rem;">
                    <div class="flex items-center gap-2 mb-4">
                        <svg class="w-3 h-3 text-ink-soft" style="color:#6b645c;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                        <span class="text-xs font-medium text-ink-soft uppercase tracking-wider" style="color:#6b645c;">SSL PROTECTED</span>
                    </div>

                    <!-- Card Element -->
                    <div class="mt-4 mb-6">
                        <label class="text-xs font-medium text-ink-soft uppercase tracking-wider block mb-2" style="color:#6b645c;">Card Details</label>
                        <div id="card-number-element" class="w-full px-4 py-3 border border-hairline rounded-lg bg-white text-sm" style="border:1px solid #E2D9C9; border-radius:0.5rem; background-color:#fff; padding:0.75rem 1rem;"></div>
                        <div id="card-errors" role="alert" class="text-red-500 text-xs mt-2"></div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 mb-6">
                        <div class="space-y-1">
                            <div id="card-expiry-element" class="w-full px-4 py-3 border border-hairline rounded-lg bg-white text-sm" style="border:1px solid #E2D9C9; border-radius:0.5rem; background-color:#fff; padding:0.75rem 1rem;"></div>
                        </div>
                        <div class="space-y-1">
                            <div id="card-cvc-element" class="w-full px-4 py-3 border border-hairline rounded-lg bg-white text-sm" style="border:1px solid #E2D9C9; border-radius:0.5rem; background-color:#fff; padding:0.75rem 1rem;"></div>
                        </div>
                    </div>

                    <button id="submit-button" type="submit" class="w-full bg-gradient-to-r from-gold to-gold-deep text-white font-semibold py-4 px-4 rounded-2xl text-sm transition-all hover:from-gold-deep hover:to-gold transform hover:scale-[1.01] active:scale-[0.99] shadow-md mt-6 flex items-center justify-center gap-2" style="background-color:#B8860B; background-image:linear-gradient(to right, #B8860B, #96690a); color:#fff; border-radius:1rem; min-height:52px;">
                        <span id="button-text">Complete Secure Payment</span>
                        <span id="button-loading" class="hidden flex items-center gap-2">
                            <svg class="animate-spin w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Processing...
                        </span>
                    </button>

                    <div class="flex items-center justify-center gap-1.5 mt-4 text-ink-soft text-xs" style="color:#6b645c;">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6-4h12a1 1 0 011 1v1a1 1 0 01-1 1H6a1 1 0 01-1-1v-1a1 1 0 011-1zm0-4h12a1 1 0 011 1v1a1 1 0 01-1 1H6a1 1 0 01-1-1v-1a1 1 0 011-1z"></path>
                        </svg>
                        <span>Secure end-to-end encryption</span>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right Column - Order Summary -->
        <div class="lg:col-span-1 lci-summary-col">
            <div class="bg-cream p-6 rounded-3xl" style="background-color:#F7EEE0; border-radius:1.5rem; padding:1.5rem;">
                <h3 class="font-serif text-xl font-semibold text-ink mb-6" style="color:#2B2620;">Order Summary</h3>

                <!-- Product Item -->
                <div class="flex gap-4 mb-6 pb-6 border-b border-hairline" style="border-bottom:1px solid #E2D9C9; padding-bottom:1.5rem; margin-bottom:1.5rem;">
                    <div id="product-image-wrap" class="w-20 h-24 bg-gradient-to-br from-amber-100 to-orange-100 rounded-xl flex items-center justify-center flex-shrink-0 overflow-hidden" style="width:5rem; height:6rem; border-radius:0.75rem; background:linear-gradient(to bottom right, #EFE3D0, #E6D5BC); display:flex; align-items:center; justify-content:center; flex-shrink:0; overflow:hidden;">
                        <svg class="w-10 h-10 text-gold" style="color:#B8860B;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div id="product-name" class="font-medium text-ink text-sm mb-1" style="color:#2B2620;">Loading…</div>
                        <div id="product-desc" class="text-xs text-ink-soft mb-2" style="color:#6b645c;">&nbsp;</div>
                        <div id="product-price" class="font-medium text-ink text-sm" style="color:#2B2620;">£0.00</div>
                    </div>
                </div>

                <!-- Price Breakdown -->
                <div class="space-y-3 mb-6 text-sm">
                    <div class="flex justify-between">
                        <span class="text-ink-soft" style="color:#6b645c;">Subtotal</span>
                        <span id="summary-subtotal" class="text-ink font-medium" style="color:#2B2620;">£0.00</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-ink-soft" style="color:#6b645c;" id="summary-shipping-label">Shipping (Standard UK)</span>
                        <span id="summary-shipping" class="text-ink font-medium" style="color:#2B2620;">£0.00</span>
                    </div>
                    <div class="flex justify-between pt-4 border-t border-hairline text-base" style="border-top:1px solid #E2D9C9; padding-top:1.25rem; margin-top:0.5rem;">
                        <span class="font-medium text-ink" style="color:#2B2620;">Total</span>
                        <span id="summary-total" class="font-bold text-ink" style="color:#2B2620;">£0.00</span>
                    </div>
                </div>

                <!-- Info Box -->
                <div class="bg-white p-4 rounded-xl border border-hairline mb-6" style="background-color:#fff; border:1px solid #E2D9C9; border-radius:0.75rem; padding:1rem;">
                    <div class="flex gap-3">
                        <svg class="w-5 h-5 text-gold flex-shrink-0 mt-0.5" style="color:#B8860B;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div class="text-xs text-ink-soft leading-relaxed" style="color:#6b645c;">
                            Your digital companion guide will be sent immediately after payment to the provided email address.
                        </div>
                    </div>
                </div>

                <!-- Quote -->
                <div class="text-center">
                    <p class="font-serif text-ink-soft italic text-sm mb-1" style="color:#6b645c;">"A sanctuary in paper form."</p>
                    <p class="text-xs text-ink-soft" style="color:#6b645c;">— VOGUE LIVING</p>
                </div>
            </div>
        </div>
    </div>


</div>

<script src="https://js.stripe.com/v3/"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Stripe initialization (split card fields to match design)
    let stripe, elements, cardNumber, cardExpiry, cardCvc;
    const stripeKey = "{{ config('services.stripe.key', env('STRIPE_PUBLISHABLE_KEY', '')) }}";
    const stripeStyle = { base: { fontSize: '14px', color: '#2B2620' } };

    if (stripeKey) {
        try {
            stripe = Stripe(stripeKey);
            elements = stripe.elements();
            cardNumber = elements.create('cardNumber', { style: stripeStyle });
            cardExpiry = elements.create('cardExpiry', { style: stripeStyle });
            cardCvc = elements.create('cardCvc', { style: stripeStyle });
            cardNumber.mount('#card-number-element');
            cardExpiry.mount('#card-expiry-element');
            cardCvc.mount('#card-cvc-element');
            [cardNumber, cardExpiry, cardCvc].forEach(el => {
                el.on('change', function(event) {
                    document.getElementById('card-errors').textContent = event.error ? event.error.message : '';
                });
            });
        } catch (e) {
            console.error('Stripe init error:', e);
        }
    }

    // State
    let product = null;
    let isSubmitting = false; // New flag to track form submission
    const UK_STANDARD_DELIVERY_COST = 4.99;

    // DOM Elements
    const deliveryOptionRadios = document.querySelectorAll('input[name="delivery_option"]');
    const paymentForm = document.getElementById('payment-form');
    const submitButton = document.getElementById('submit-button');
    const buttonText = document.getElementById('button-text');
    const buttonLoading = document.getElementById('button-loading');
    const cardErrors = document.getElementById('card-errors');

    const productNameEl = document.getElementById('product-name');
    const productDescEl = document.getElementById('product-desc');
    const productPriceEl = document.getElementById('product-price');
    const productImageWrap = document.getElementById('product-image-wrap');
    const summarySubtotal = document.getElementById('summary-subtotal');
    const summaryShipping = document.getElementById('summary-shipping');
    const summaryShippingLabel = document.getElementById('summary-shipping-label');
    const summaryTotal = document.getElementById('summary-total');

    // Fetch the product (single-product checkout page)
    async function fetchProduct() {
        try {
            const response = await fetch('/api/shop/products');
            const data = await response.json();
            const products = data.products || [];
            product = products.find(p => /wellbeing journal/i.test(p.name)) || products[0] || null;

            if (product) {
                productNameEl.textContent = product.name;
                productDescEl.textContent = product.description || 'The 2024 Collection Edition';
                productPriceEl.textContent = `£${parseFloat(product.price).toFixed(2)}`;
                if (product.image) {
                    productImageWrap.innerHTML = `<img src="${product.image}" alt="${product.name}" class="w-full h-full object-cover" />`;
                }
            } else {
                productNameEl.textContent = 'Product unavailable';
                productPriceEl.textContent = '£0.00';
            }
        } catch (error) {
            console.error('Error fetching product:', error);
            productNameEl.textContent = 'Error loading product';
        }
        updateSummary();
    }

    // Update delivery option styles + recompute totals
    function updateDeliveryOptionStyles() {
        document.querySelectorAll('.delivery-option').forEach(label => {
            const radio = label.querySelector('input[type="radio"]');
            if (radio.checked) {
                label.style.border = '2px solid #B8860B';
                label.style.backgroundColor = '#F7EEE0';
            } else {
                label.style.border = '2px solid #E2D9C9';
                label.style.backgroundColor = 'transparent';
            }
        });
    }

    function getSelectedDelivery() {
        const checked = document.querySelector('input[name="delivery_option"]:checked');
        const label = checked.closest('.delivery-option');
        return {
            value: checked.value,
            cost: parseFloat(label.dataset.cost || '0'),
            name: label.querySelector('.font-medium').textContent.trim()
        };
    }

    function updateSummary() {
        const subtotal = product ? parseFloat(product.price) : 0;
        const delivery = getSelectedDelivery();
        const total = subtotal + delivery.cost;

        summarySubtotal.textContent = `£${subtotal.toFixed(2)}`;
        summaryShippingLabel.textContent = delivery.value === 'collection' ? 'Shipping (Collection)' : 'Shipping (Standard UK)';
        summaryShipping.textContent = delivery.cost > 0 ? `£${delivery.cost.toFixed(2)}` : 'Free';
        summaryTotal.textContent = `£${total.toFixed(2)}`;
    }

    updateDeliveryOptionStyles();
    deliveryOptionRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            updateDeliveryOptionStyles();
            updateSummary();
        });
    });

    // Real-time validation: clear errors when user interacts with fields
    const allFields = ['first_name', 'last_name', 'customer_email', 'phone_number', 'street_address', 'city', 'postcode'];
    allFields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field) {
            field.addEventListener('focus', () => {
                if (!isSubmitting) { // Only clear errors if not submitting
                    const input = document.getElementById(fieldId);
                    const errorSpan = document.getElementById(`${fieldId}_error`);
                    if (input) input.classList.remove('form-input-invalid');
                    if (errorSpan) errorSpan.textContent = '';
                }
            });
            field.addEventListener('input', () => {
                if (!isSubmitting) { // Only clear errors if not submitting
                    const input = document.getElementById(fieldId);
                    const errorSpan = document.getElementById(`${fieldId}_error`);
                    if (input) input.classList.remove('form-input-invalid');
                    if (errorSpan) errorSpan.textContent = '';
                }
            });
        }
    });

    // Filter phone number input to only allow digits, spaces, and +
    const phoneNumberField = document.getElementById('phone_number');
    if (phoneNumberField) {
        phoneNumberField.addEventListener('input', function(e) {
            // Allow only digits, spaces, and +
            this.value = this.value.replace(/[^0-9+\s]/g, '');
        });
    }

    // Warn user before leaving page while processing
    window.addEventListener('beforeunload', function(e) {
        if (isSubmitting) {
            e.preventDefault();
            e.returnValue = '';
            return '';
        }
    });

    // Loading state
    function setLoadingState(loading) {
        submitButton.disabled = loading;
        buttonText.classList.toggle('hidden', loading);
        buttonLoading.classList.toggle('hidden', !loading);
    }

    // Validation helper functions
    function clearErrors() {
        const inputs = ['first_name', 'last_name', 'customer_email', 'phone_number', 'street_address', 'city', 'postcode'];
        inputs.forEach(id => {
            const input = document.getElementById(id);
            const errorSpan = document.getElementById(`${id}_error`);
            if (input) input.classList.remove('form-input-invalid');
            if (errorSpan) errorSpan.textContent = '';
        });
        cardErrors.textContent = '';
    }

    function setFieldError(fieldId, message) {
        const input = document.getElementById(fieldId);
        const errorSpan = document.getElementById(`${fieldId}_error`);
        if (input) input.classList.add('form-input-invalid');
        if (errorSpan) errorSpan.textContent = message;
    }

    function scrollToField(fieldId) {
        const input = document.getElementById(fieldId);
        if (input) {
            input.scrollIntoView({ behavior: 'smooth', block: 'center' });
            input.focus();
        }
    }

    function validateEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    function validateUKPhone(phone) {
        const digitsOnly = phone.replace(/\D/g, '');
        return digitsOnly.length >= 10 && digitsOnly.length <= 11;
    }

    function validateUKPostcode(postcode) {
        // UK postcode regex (allows uppercase/lowercase, optional space)
        const postcodeRegex = /^[A-Z]{1,2}\d[A-Z\d]?\s?\d[A-Z]{2}$/i;
        return postcodeRegex.test(postcode.trim());
    }

    function validateForm() {
        clearErrors();
        let isValid = true;
        let firstInvalidFieldId = null;

        const firstName = document.getElementById('first_name').value.trim();
        if (!firstName) {
            setFieldError('first_name', 'First name is required');
            isValid = false;
            if (!firstInvalidFieldId) firstInvalidFieldId = 'first_name';
        }

        const lastName = document.getElementById('last_name').value.trim();
        if (!lastName) {
            setFieldError('last_name', 'Last name is required');
            isValid = false;
            if (!firstInvalidFieldId) firstInvalidFieldId = 'last_name';
        }

        const customerEmail = document.getElementById('customer_email').value.trim();
        if (!customerEmail) {
            setFieldError('customer_email', 'Email address is required');
            isValid = false;
            if (!firstInvalidFieldId) firstInvalidFieldId = 'customer_email';
        } else if (!validateEmail(customerEmail)) {
            setFieldError('customer_email', 'Please enter a valid email (e.g., name@example.com)');
            isValid = false;
            if (!firstInvalidFieldId) firstInvalidFieldId = 'customer_email';
        }

        const phoneNumber = document.getElementById('phone_number').value.trim();
        if (!phoneNumber) {
            setFieldError('phone_number', 'Phone number is required');
            isValid = false;
            if (!firstInvalidFieldId) firstInvalidFieldId = 'phone_number';
        } else {
            const digitsOnly = phoneNumber.replace(/\D/g, '');
            if (digitsOnly.length < 10) {
                setFieldError('phone_number', 'Phone number must have at least 10 digits');
                isValid = false;
                if (!firstInvalidFieldId) firstInvalidFieldId = 'phone_number';
            } else if (digitsOnly.length > 11) {
                setFieldError('phone_number', 'Phone number must have no more than 11 digits');
                isValid = false;
                if (!firstInvalidFieldId) firstInvalidFieldId = 'phone_number';
            }
        }

        const delivery = getSelectedDelivery();
        if (delivery.value === 'standard_uk') {
            const streetAddress = document.getElementById('street_address').value.trim();
            const city = document.getElementById('city').value.trim();
            const postcode = document.getElementById('postcode').value.trim();

            if (!streetAddress) {
                setFieldError('street_address', 'Street address is required');
                isValid = false;
                if (!firstInvalidFieldId) firstInvalidFieldId = 'street_address';
            }

            if (!city) {
                setFieldError('city', 'City is required');
                isValid = false;
                if (!firstInvalidFieldId) firstInvalidFieldId = 'city';
            }

            if (!postcode) {
                setFieldError('postcode', 'Postcode is required');
                isValid = false;
                if (!firstInvalidFieldId) firstInvalidFieldId = 'postcode';
            } else if (!validateUKPostcode(postcode)) {
                setFieldError('postcode', 'Please enter a valid UK postcode');
                isValid = false;
                if (!firstInvalidFieldId) firstInvalidFieldId = 'postcode';
            }
        }

        if (!isValid && firstInvalidFieldId) {
            scrollToField(firstInvalidFieldId);
        }

        return isValid;
    }

    // Real payment form submission
    paymentForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        isSubmitting = true; // Set submitting flag

        if (!stripe || !cardNumber) {
            cardErrors.textContent = 'Payment system unavailable.';
            isSubmitting = false;
            return;
        }
        if (!product) {
            cardErrors.textContent = 'Product unavailable, please refresh and try again.';
            isSubmitting = false;
            return;
        }

        // Validate form before proceeding
        if (!validateForm()) {
            setLoadingState(false);
            isSubmitting = false; // Reset flag on validation failure
            return;
        }

        setLoadingState(true);
        cardErrors.textContent = '';

        const delivery = getSelectedDelivery();
        const firstName = document.getElementById('first_name').value.trim();
        const lastName = document.getElementById('last_name').value.trim();
        const phoneNumber = document.getElementById('phone_number').value.trim();
        const street = document.getElementById('street_address').value.trim();
        const city = document.getElementById('city').value.trim();
        const postcode = document.getElementById('postcode').value.trim();
        const deliveryAddress = [street, city, postcode].filter(Boolean).join(', ');
        const customerEmail = document.getElementById('customer_email').value.trim();

        try {
            const orderResponse = await fetch('/api/shop/orders', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    first_name: firstName,
                    last_name: lastName,
                    phone_number: phoneNumber,
                    street_address: street,
                    city: city,
                    postcode: postcode,
                    items: [{ product_id: product.id, quantity: 1 }],
                    delivery_option: delivery.value,
                    delivery_address: deliveryAddress || null,
                    customer_email: customerEmail
                })
            });

            const orderData = await orderResponse.json();
            if (!orderResponse.ok) {
                // Handle backend validation errors
                if (orderData.errors) {
                    clearErrors();
                    let firstInvalidFieldId = null;
                    for (const [field, messages] of Object.entries(orderData.errors)) {
                        const fieldId = field.replace('.', '_');
                        if (messages.length > 0) {
                            setFieldError(fieldId, messages[0]);
                            if (!firstInvalidFieldId) firstInvalidFieldId = fieldId;
                        }
                    }
                    if (firstInvalidFieldId) {
                        scrollToField(firstInvalidFieldId);
                    }
                } else {
                    cardErrors.textContent = orderData.message || 'Order creation failed.';
                }
                setLoadingState(false);
                return;
            }

            const { clientSecret, order_id } = orderData;

            const { paymentIntent, error } = await stripe.confirmCardPayment(clientSecret, {
                payment_method: { card: cardNumber }
            });

            if (error) {
                cardErrors.textContent = error.message;
                setLoadingState(false);
                return;
            }

            if (paymentIntent.status === 'succeeded') {
                const confirmResponse = await fetch('/api/shop/orders/confirm', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        payment_intent_id: paymentIntent.id,
                        order_id: order_id
                    })
                });

                if (!confirmResponse.ok) throw new Error('Order confirmation failed.');

                alert('Order placed successfully!');
                paymentForm.reset();
                cardNumber.clear();
                cardExpiry.clear();
                cardCvc.clear();
                
                // Clear all contact and address fields
                document.getElementById('first_name').value = '';
                document.getElementById('last_name').value = '';
                document.getElementById('customer_email').value = '';
                document.getElementById('phone_number').value = '';
                document.getElementById('street_address').value = '';
                document.getElementById('city').value = '';
                document.getElementById('postcode').value = '';
                
                // Reset delivery method to default (Standard UK Delivery)
                document.querySelector('input[name="delivery_option"][value="standard_uk"]').checked = true;
                updateDeliveryOptionStyles();
            }
        } catch (error) {
            console.error('Checkout error:', error);
            cardErrors.textContent = error.message || 'Checkout error.';
        } finally {
            setLoadingState(false);
            isSubmitting = false; // Reset submitting flag no matter what
        }
    });

    fetchProduct();
});
</script>
@endsection