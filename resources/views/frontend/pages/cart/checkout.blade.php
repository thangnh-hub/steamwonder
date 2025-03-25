<!DOCTYPE html>
<html lang="{{ $locale ?? 'vi' }}">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        {{ $page->json_params->name->$locale ?? ($page->name ?? ($page->json_params->seo_title->$locale ?? ($setting->{$locale . '-seo_title'} ?? ($setting->seo_title ?? '')))) }}
    </title>
    <link rel="icon" href="{{ json_decode($setting->image)->favicon ?? '' }}" type="image/x-icon">
    {{-- Print SEO --}}
    @php
        $seo_title = $seo_title ?? ($page->json_params->seo_title->$locale ?? ($setting->{$locale . '-seo_title'} ?? ($setting->seo_title ?? '')));
        $seo_keyword = $seo_keyword ?? ($page->json_params->seo_keyword->$locale ?? ($setting->{$locale . '-seo_keyword'} ?? ($setting->seo_keyword ?? '')));
        $seo_description = $seo_description ?? ($page->json_params->seo_description->$locale ?? ($setting->{$locale . '-seo_description'} ?? ($setting->seo_description ?? '')));
        $seo_image = $seo_image ?? ($page->json_params->image ?? (json_decode($setting->image)->seo_og_image ?? ''));
    @endphp
    <meta name="description" content="{{ $seo_description }}" />
    <meta name="keywords" content="{{ $seo_keyword }}" />
    <meta name="news_keywords" content="{{ $seo_keyword }}" />
    <meta property="og:image" content="{{ $seo_image }}" />
    <meta property="og:title" content="{{ $seo_title }}" />
    <meta property="og:description" content="{{ $seo_description }}" />
    <meta property="og:url" content="{{ Request::fullUrl() }}" />
    {{-- End Print SEO --}}
    {{-- Include style for app --}}
    @include('frontend.panels.styles')
    {{-- Styles custom each page --}}
    @stack('style')

</head>

<body class="page">
    <div id="page" class="hfeed page-wrapper">

        @isset($widget->header)
            @if (\View::exists('frontend.widgets.header.' . $widget->header->json_params->layout))
                @include('frontend.widgets.header.' . $widget->header->json_params->layout)
            @else
                {{ 'View: frontend.widgets.header.' . $widget->header->json_params->layout . ' do not exists!' }}
            @endif
        @endisset

        <div id="site-main" class="site-main">
            <div id="main-content" class="main-content">
                <div id="primary" class="content-area">
                    <div id="title" class="page-title">
                        <div class="section-container">
                            <div class="content-title-heading">
                                <h1 class="text-title-heading">
                                    Checkout
                                </h1>
                            </div>
                            <div class="breadcrumbs">
                                <a href="index.html">Home</a><span class="delimiter"></span><a
                                    href="shop-grid-left.html">Shop</a><span class="delimiter"></span>Shopping Cart
                            </div>
                        </div>
                    </div>
                    <div class="woocommerce-page-header">
                        <ul>
                            <li class="shopping-cart-link line-hover ">
                                <a href="{{ route('frontend.order.cart') }}">Shopping Cart<span
                                        class="cart-count">(1)</span></a>
                            </li>
                            <li class="checkout-link line-hover active"><a
                                    href="{{ route('frontend.order.checkout') }}">Checkout</a></li>
                            <li class="order-tracking-link"><a href="{{ route('frontend.order.checkout') }}">Order
                                    Tracking</a></li>
                        </ul>
                    </div>
                    <div id="content" class="site-content" role="main">
                        <div class="section-padding">
                            <div class="section-container p-l-r">
                                <div class="shop-checkout">
                                    @if (session('cart'))

                                        <form action="{{ route('frontend.order.coupon.add') }}" method="POST">
                                            @csrf
                                            <p class="text-default text-center c-pointer have_coupon">You have a coupon
                                                code?</p>
                                            <div
                                                class="form-group discount_block {{ Session::get('coupon') ? '' : 'd-none' }}">
                                                <input type="text" name="coupon_code"
                                                    class="input-text form-control mb-2 coupon_code"
                                                    placeholder="Coupon code"
                                                    value="{{ Session::get('coupon') ? session('coupon')['coupon_code'] : '' }}">
                                                @php $subtotal_coupon_from = 0 @endphp
                                                @foreach (session('cart') as $details_c)
                                                    @foreach ($details_c as $details_c_chil)
                                                        @php
                                                            $subtotal_coupon_from += $details_c_chil['price'] * $details_c_chil['quantity'];
                                                        @endphp
                                                    @endforeach
                                                @endforeach
                                                <input type="hidden"
                                                    name="amount_sub"value="{{ $subtotal_coupon_from }}">
                                                <button type="submit" class="btn btn-dark apply_coupon "
                                                    value="Apply coupon"><i class="fa fa-gift"></i> Apply
                                                    coupon</button>
                                                @if (Session::get('coupon'))
                                                    <a
                                                        onclick="return confirm('Delete this coupon??')"href="{{ route('frontend.order.coupon.del') }}">
                                                        <button type="button" class="btn btn-dark delete_coupon"><i
                                                                class="fa fa-trash"></i>Delete coupon</button>
                                                    </a>
                                                @endif
                                            </div>
                                        </form>
                                        <form name="checkout" method="get" class="checkout form-checkout"
                                            action="{{ route('processTransaction') }}">
                                            @csrf
                                            <div class="row">
                                                <div class="col-xl-8 col-lg-7 col-md-12 col-12">
                                                    <div class="customer-details">
                                                        <div class="billing-fields">
                                                            <h3>Billing details </h3>
                                                            <div class="billing-fields-wrapper">
                                                                <p class="form-row form-row-last validate-required">
                                                                    <label>Fullname <span class="required"
                                                                            title="required">*</span></label>
                                                                    <span class="input-wrapper"><input type="text"
                                                                            class="input-text" id="name" required
                                                                            name="name"
                                                                            value="{{ $user_auth->name ?? old('name') }}"></span>
                                                                </p>

                                                                <p class="form-row form-row-wide validate-required">
                                                                    <label>Country / Region <span class="required"
                                                                            title="required">*</span></label>
                                                                    <span class="input-wrapper">
                                                                        <select name="json_params[country]"
                                                                            required=""
                                                                            class="country-select custom-select">
                                                                            <option value="">Select a country /
                                                                                region…</option>
                                                                            @foreach ($country as $val)
                                                                                <option
                                                                                    {{ old('json_params.country') == $val->id ? 'selected' : '' }}
                                                                                    {{ isset($user_auth->country_id) && $user_auth->country_id == $val->id ? 'selected' : '' }}
                                                                                    value="{{ $val->id }}">
                                                                                    {{ $val->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </span>
                                                                </p>
                                                                <p
                                                                    class="form-row address-field validate-required form-row-wide">
                                                                    <label for="billing_city" class="">City
                                                                        <span class="required"
                                                                            title="required">*</span></label>
                                                                    <span class="input-wrapper">
                                                                        <select required name="json_params[city]"
                                                                            class="city-select custom-select">

                                                                        </select>
                                                                    </span>
                                                                </p>
                                                                <p
                                                                    class="form-row address-field validate-required form-row-wide">
                                                                    <label>Street address <span class="required"
                                                                            title="required">*</span></label>
                                                                    <span class="input-wrapper">
                                                                        <input type="text" class="input-text"
                                                                            id=""name="json_params[address]"
                                                                            value="{{ old('json_params.address') }}"
                                                                            placeholder="House number and street name">
                                                                    </span>
                                                                </p>



                                                                <p
                                                                    class="form-row form-row-wide validate-required validate-phone">
                                                                    <label>Phone <span class="required"
                                                                            title="required">*</span></label>
                                                                    <span class="input-wrapper">
                                                                        <input type="tel" class="input-text"
                                                                            id="phone" name="phone" required
                                                                            value="{{ $user_auth->phone ?? old('phone') }}">
                                                                    </span>
                                                                </p>
                                                                <p
                                                                    class="form-row form-row-wide validate-required validate-email">
                                                                    <label>Email address <span class="required"
                                                                            title="required">*</span></label>
                                                                    <span class="input-wrapper">
                                                                        <input type="email" required
                                                                            class="input-text"
                                                                            id="checkout_id"name="email"
                                                                            value="{{ $user_auth->email ?? old('email') }}">
                                                                    </span>
                                                                </p>
                                                                <p
                                                                    class="form-row address-field validate-required validate-postcode form-row-wide">
                                                                    <label>Postcode / ZIP </label>
                                                                    <span class="input-wrapper">
                                                                        <input type="text" class="input-text"
                                                                            name="transaction_code"
                                                                            value="{{ old('transaction_code') }}">
                                                                    </span>
                                                                </p>
                                                                <p class="form-row notes">
                                                                    <label>Order notes <span
                                                                            class="optional">(optional)</span></label>
                                                                    <span class="input-wrapper">
                                                                        <textarea name="json_params[note]" class="input-text"
                                                                            placeholder="Notes about your order, e.g. special notes for delivery." rows="2" cols="5">{{ old('json_params[note]') }}</textarea>
                                                                    </span>
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="account-fields">
                                                            <p class="form-row form-row-wide">
                                                                <label class="checkbox">
                                                                    <input class="input-checkbox" type="checkbox"
                                                                        name="createaccount" value="1">
                                                                    <span>Create an account?</span>
                                                                </label>
                                                            </p>
                                                            <div class="create-account">
                                                                <p class="form-row validate-required">
                                                                    <label>Create account password <span
                                                                            class="required"
                                                                            title="required">*</span></label>
                                                                    <span class="input-wrapper password-input">
                                                                        <input type="password" class="input-text"
                                                                            name="account_password" value=""
                                                                            autocomplete="off">
                                                                        <span class="show-password-input"></span>
                                                                    </span>
                                                                </p>
                                                                <div class="clear"></div>
                                                            </div>
                                                        </div>
                                                    </div>


                                                </div>
                                                <div class="col-xl-4 col-lg-5 col-md-12 col-12">
                                                    <div class="checkout-review-order">
                                                        <div class="checkout-review-order-table">
                                                            <div class="review-order-title">Product</div>
                                                            <div class="cart-items">
                                                                @php $subtotal = 0 @endphp
                                                                @foreach (session('cart') as $id => $items_cart)
                                                                    @foreach ($items_cart as $details)
                                                                        @php
                                                                            $subtotal += $details['price'] * $details['quantity'];
                                                                        @endphp
                                                                        <div class="cart-item">
                                                                            <div class="info-product">
                                                                                <div class="product-thumbnail">
                                                                                    <img width="600" height="600"
                                                                                        src="{{ $details['image_thumb'] ?? $details['image'] }}"
                                                                                        alt="{{ $details['title'] }}">
                                                                                </div>
                                                                                <div class="product-name">
                                                                                    {{ $details['title'] }}
                                                                                    <strong
                                                                                        class="product-quantity">QTY :
                                                                                        {{ $details['quantity'] }}</strong>
                                                                                    <strong class="product-quantity">
                                                                                        <span
                                                                                            class="size">@lang('Size'):
                                                                                            {{ $details['size'] ? $details['size'] : '' }}
                                                                                        </span>
                                                                                        <span
                                                                                            class="color ml-2">@lang('Color'):
                                                                                            <span class="box_bg_color"
                                                                                                style="background-color: {{ $details['color'] ? $details['color'] : '' }}"></span></span>

                                                                                    </strong>
                                                                                </div>
                                                                            </div>
                                                                            <div class="product-total">
                                                                                <span>${{ number_format($details['price'], 2) }}</span>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                @endforeach
                                                            </div>
                                                            <div class="cart-subtotal">
                                                                <h2>Subtotal</h2>
                                                                <div class="subtotal-price">
                                                                    <span
                                                                        class="">${{ number_format($subtotal, 2) }}</span>
                                                                    <input
                                                                        type="hidden"value="{{ $subtotal }}"class="subtotal-price-value">
                                                                </div>
                                                            </div>
                                                            @if (Session::get('coupon'))
                                                                @php
                                                                    if (session('coupon')['coupon_type'] == 'pecent') {
                                                                        $total_coupon = ($subtotal * session('coupon')['discount']) / 100;
                                                                    } else {
                                                                        $total_coupon = round(session('coupon')['discount']);
                                                                    }
                                                                    $subtotal = $subtotal - $total_coupon;
                                                                @endphp

                                                                <div class="cart-subtotal">
                                                                    <h2>Coupon (Discount
                                                                        {{ session('coupon')['discount'] }}{{ session('coupon')['coupon_type'] == 'pecent' ? '%' : "$" }})
                                                                    </h2>
                                                                    <div class="subtotal-price">
                                                                        <span
                                                                            class="">${{ number_format($total_coupon, 2) }}</span>
                                                                        <input type="hidden" name="discount"
                                                                            value="{{ number_format($total_coupon, 2) }}"class="discount-price-value">
                                                                        @if (isset(session('coupon')['id']) && session('coupon')['id'] != '')
                                                                            <input type="hidden"
                                                                                name="json_params[id_discount]"
                                                                                value="{{ session('coupon')['id'] }}"class="id-discount-value">
                                                                        @endif

                                                                    </div>
                                                                </div>
                                                            @endif
                                                            <input
                                                                type="hidden"value="{{ $total_coupon ?? 0 }}"class="subtotal-coupon">
                                                            <div class="shipping-totals shipping">
                                                                <h2>Shipping</h2>
                                                                <div data-title="Shipping">
                                                                    <ul class="shipping-methods custom-radio">
                                                                        <li><input type="radio" name="ship_fee"
                                                                                data-index="0" value="0"checked
                                                                                class="shipping_method"><label>Free
                                                                                Ship </label></li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                            <div class="order-total">
                                                                <h2>Total</h2>
                                                                <div class="total-price">
                                                                    <strong>
                                                                        <span
                                                                            class="total-price-value">${{ number_format($subtotal, 2) }}</span>
                                                                        <input type="hidden"
                                                                            value="{{ $subtotal }}"
                                                                            class="total-total" name="total">
                                                                    </strong>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div id="payment" class="checkout-payment">
                                                            <ul class="payment-methods methods custom-radio">
                                                                <li class="payment-method" data-method="get"
                                                                    data-action="{{ route('processTransaction') }}">
                                                                    <input type="radio" checked
                                                                        class="input-radio input-radio-change"
                                                                        name="type_order" value="paypal">
                                                                    <label>PayPal</label>
                                                                    <img src="https://www.paypalobjects.com/webstatic/mktg/Logo/AM_mc_vs_ms_ae_UK.png"
                                                                        alt="PayPal acceptance mark">
                                                                    <div class="payment-box">
                                                                        <p>Pay via PayPal; you can pay with your credit
                                                                            card if you don’t have a PayPal account.</p>
                                                                    </div>
                                                                </li>
                                                                <li class="payment-method" data-method="post"
                                                                    data-action="{{ route('stripe.payment') }}">
                                                                    <input type="radio"
                                                                        class="input-radio input-radio-change"
                                                                        name="type_order" value="stripe">
                                                                    <label class="font-weight-bold">Stripe</label>
                                                                    <img src="https://ps.w.org/woocommerce-gateway-stripe/assets/banner-1544x500.png?rev=2419673"
                                                                        alt="Stripe payment">
                                                                    <div class="payment-box">
                                                                        <p>Millions of companies of all sizes—from
                                                                            startups to Fortune 500s—use Stripe's
                                                                            software and APIs to accept payments, send
                                                                            payouts, and manage their businesses online.
                                                                        </p>
                                                                    </div>
                                                                </li>
                                                            </ul>

                                                            <div class="form-row place-order">
                                                                <div class="terms-and-conditions-wrapper">
                                                                    <div class="privacy-policy-text"></div>
                                                                </div>
                                                                <button type="submit" class="button alt"
                                                                    value="Place order">Place order</button>
                                                            </div>
                                                        </div>
                                                        {{-- <a href="{{ route('frontend.stripe') }}" class="checkout-payment">
                                                    <ul class="payment-methods methods">

                                                    </ul>
                                                </a> --}}
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    @else
                                        <div class="entry-content clearfix">
                                            <div class="woocommerce">
                                                <div class="woocommerce-notices-wrapper"></div>
                                                <p class="cart-empty woocommerce-info">Your cart is currently empty.
                                                </p>
                                                <p class="return-to-shop">
                                                    <a class="button wc-backward" href="/">Return to shop</a>
                                                </p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div><!-- #content -->
                </div><!-- #primary -->
            </div><!-- #main-content -->
        </div>

        @isset($widget->footer)
            @if (\View::exists('frontend.widgets.footer.' . $widget->footer->json_params->layout))
                @include('frontend.widgets.footer.' . $widget->footer->json_params->layout)
            @else
                {{ 'View: frontend.widgets.footer.' . $widget->footer->json_params->layout . ' do not exists!' }}
            @endif
        @endisset
    </div>
    {{-- Include scripts --}}
    @include('frontend.panels.scripts')
    @include('frontend.components.sticky.alert')
    {{-- Scripts custom each page --}}
    @stack('script')

</body>

</html>
