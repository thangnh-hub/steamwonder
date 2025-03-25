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

                    <div id="content" class="site-content" role="main">
                        <div class="section-padding">
                            <div class="section-container p-l-r">
                                @if ($rows)
                                    <div class="shop-checkout">
                                        <div class="woocommerce-order">
                                            <p
                                                class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received">
                                                @lang('Thank you. Your order has been received.')</p>
                                            <ul
                                                class="woocommerce-order-overview woocommerce-thankyou-order-details order_details">
                                                <li class="woocommerce-order-overview__order order">
                                                    Order number: <strong>{{ $rows->id }}</strong>
                                                </li>
                                                <li class="woocommerce-order-overview__date date">
                                                    Date:
                                                    <strong>{{ date('M d, Y', strtotime($rows->created_at)) }}</strong>
                                                </li>
                                                <li class="woocommerce-order-overview__total total">
                                                    Total: <strong><span
                                                            class="woocommerce-Price-amount amount"><bdi><span
                                                                    class="woocommerce-Price-currencySymbol">$</span>{{ $rows->total }}</bdi></span></strong>
                                                </li>

                                                <li class="woocommerce-order-overview__payment-method method">
                                                    Email: <strong>{{ $rows->email }}</strong>
                                                </li>
                                            </ul>
                                            <section class="woocommerce-order-details">
                                                <h2 class="woocommerce-order-details__title">@lang('Order details')</h2>
                                                <table
                                                    class="woocommerce-table woocommerce-table--order-details shop_table order_details">
                                                    <thead>
                                                        <tr>
                                                            <th class="woocommerce-table__product-name product-name">
                                                                @lang('Product ')</th>
                                                            <th class="woocommerce-table__product-table product-total">
                                                                @lang('Total')</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                        @php
                                                            $OrderDetail = App\Models\OrderDetail::where('order_id', $order_id)->get();
                                                            $payment_status = App\Consts::PAYMENT_STATUS[$rows->payment_status];
                                                            $order_status = App\Consts::ORDER_STATUS[$rows->status];
                                                        @endphp
                                                        @if ($OrderDetail)
                                                            @foreach ($OrderDetail as $detail)
                                                                @php
                                                                    $detail_product = App\Models\CmsProduct::where('id', $detail->item_id)->first();
                                                                @endphp
                                                                @if ($detail_product)
                                                                    <tr class="woocommerce-table__line-item order_item">
                                                                        <td
                                                                            class="woocommerce-table__product-name product-name">
                                                                            <a
                                                                                href="#">{{ $detail_product->name }}</a>
                                                                            <strong
                                                                                class="product-quantity">×&nbsp;{{ $detail->quantity }}</strong>
                                                                        </td>
                                                                        <td
                                                                            class="woocommerce-table__product-total product-total">
                                                                            <span
                                                                                class="woocommerce-Price-amount amount"><bdi><span
                                                                                        class="woocommerce-Price-currencySymbol">$</span>{{ $detail->price * $detail->quantity }}</bdi></span>
                                                                        </td>
                                                                    </tr>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    </tbody>
                                                    <tfoot>

                                                        <tr>
                                                            <th scope="row">@lang('Discount'):</th>
                                                            <td>${{ $rows->discount }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">@lang('Shipping'):</th>
                                                            <td>${{ $rows->shipping }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">@lang('Payment Status'):</th>
                                                            <td>{{ $payment_status }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">@lang('Order Status'):</th>
                                                            <td>{{ $order_status }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">@lang('Total'):</th>
                                                            <td><span class="woocommerce-Price-amount amount"><span
                                                                        class="woocommerce-Price-currencySymbol">$</span>{{ $rows->total }}</span>
                                                            </td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </section>
                                        </div>
                                    </div>
                                @else
                                    <ul class="woocommerce-error" role="alert">
                                        <li>@lang('Sorry, the order could not be found. Please contact us if you are having difficulty finding your order details.')</li>
                                    </ul>
                                    <a class="float-right" href="/"><button
                                            class="btn btn-dark">@lang('Back to Home')</button></a>
                                @endif
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
