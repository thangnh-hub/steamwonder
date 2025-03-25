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
                                    Shopping Cart
                                </h1>
                            </div>
                            <div class="breadcrumbs">
                                <a href="/">Home</a><span class="delimiter"></span>Shopping Cart
                            </div>
                        </div>
                    </div>

                    <div id="content" class="site-content" role="main">
                        <div class="section-padding">
                            <div class="section-container p-l-r">

                                @if (session('cart'))
                                    <div class="woocommerce-page-header">
                                        <ul>
                                            <li class="shopping-cart-link line-hover active">
                                                <a href="{{ route('frontend.order.cart') }}">Shopping Cart<span
                                                        class="cart-count">(1)</span></a>
                                            </li>
                                            <li class="checkout-link line-hover "><a
                                                    href="{{ route('frontend.order.checkout') }}">Checkout</a></li>
                                            <li class="order-tracking-link"><a
                                                    href="{{ route('frontend.order.checkout') }}">Order Tracking</a>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="shop-cart">
                                        <div class="row">
                                            <div class="col-xl-8 col-lg-12 col-md-12 col-12">
                                                <form class="cart-form" action="" method="post">
                                                    <div class="table-responsive">
                                                        <table class="cart-items table" cellspacing="0">
                                                            <thead>
                                                                <tr>
                                                                    <th class="product-thumbnail">@lang('Product')</th>
                                                                    <th class="product-price">@lang('Price')</th>
                                                                    <th class="product-quantity">@lang('Quantity')</th>
                                                                    <th class="product-subtotal">@lang('Subtotal')</th>
                                                                    <th class="product-remove">&nbsp;</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @php $total = 0 @endphp

                                                                @foreach (session('cart') as $id => $items_cart)
                                                                    @foreach ($items_cart as $key => $details)
                                                                        @php
                                                                            $total += $details['price'] * $details['quantity'];
                                                                        @endphp
                                                                        <tr data-id='{{ $details['id'] }}' data-arr="{{$key}}"
                                                                            class="cart-item">
                                                                            <td class="product-thumbnail">
                                                                                <a
                                                                                    href="{{ route('frontend.page', ['taxonomy' => $details['alias'] ?? '']) }}">
                                                                                    <img width="600" height="600"
                                                                                        src="{{ $details['image_thumb'] ?? $details['image'] }}"
                                                                                        class="product-image"
                                                                                        alt="{{ $details['title'] }}">
                                                                                </a>
                                                                                <div class="product-name ssssss">
                                                                                    <a
                                                                                        href="{{ route('frontend.page', ['taxonomy' => $details['alias'] ?? '']) }}">{{ $details['title'] }}</a>

                                                                                    <div>
                                                                                        <span
                                                                                            class="size">@lang('Size'):
                                                                                            {{ $details['size'] ? $details['size'] : '' }}
                                                                                        </span>
                                                                                        <span
                                                                                            class="color ml-2">@lang('Color'):
                                                                                            <span class="box_bg_color"
                                                                                                style="background-color: {{ $details['color'] ? $details['color'] : '' }}"></span></span>

                                                                                    </div>
                                                                                </div>


                                                                            </td>
                                                                            <td class="product-price">
                                                                                <span class="price">$<span
                                                                                        class="price_num">{{ number_format($details['price'], 2) }}</span></span>
                                                                            </td>
                                                                            <td class="product-quantity">
                                                                                <div class="quantity">
                                                                                    <button type="button"
                                                                                        class="minus">-</button>
                                                                                    <input type="number"
                                                                                        class="qty update-cart"
                                                                                        step="1" min="0"
                                                                                        max="" name="quantity"
                                                                                        value="{{ $details['quantity'] }}"
                                                                                        title="Qty" size="4"
                                                                                        placeholder=""
                                                                                        inputmode="numeric"
                                                                                        autocomplete="off">
                                                                                    <button type="button"
                                                                                        class="plus">+</button>
                                                                                </div>
                                                                            </td>
                                                                            <td class="product-subtotal">
                                                                                <span>${{ number_format($details['price'] * $details['quantity'], 2) }}</span>
                                                                            </td>
                                                                            <td class="product-remove">
                                                                                <a href="#"
                                                                                    data-id='{{ $details['id'] }}'
                                                                                    data-arr='{{$key}}'
                                                                                    class="remove remove-card">×</a>
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="col-xl-4 col-lg-12 col-md-12 col-12">
                                                <div class="cart-totals">
                                                    <h2>Cart totals</h2>
                                                    <div>
                                                        <div class="cart-subtotal">
                                                            <div class="title">Subtotal</div>
                                                            <div><span>${{ number_format($total, 2) }}</span></div>
                                                        </div>

                                                        <div class="order-total">
                                                            <div class="title">Total</div>
                                                            <div><span>${{ number_format($total, 2) }}</span></div>
                                                        </div>
                                                    </div>
                                                    <div class="proceed-to-checkout">
                                                        <a href="{{ route('frontend.order.checkout') }}"
                                                            class="checkout-button button">
                                                            Proceed to checkout
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="shop-cart-empty">
                                        <div class="notices-wrapper">
                                            <p class="cart-empty">Your cart is currently empty.</p>
                                        </div>
                                        <div class="return-to-shop">
                                            <a class="button" href="/">
                                                Return to shop
                                            </a>
                                        </div>
                                    </div>
                                @endif
                                {{-- @dd(session('cart')) --}}
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
