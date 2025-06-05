@php
    if (isset($menu)) {
        $menu_header = $menu->first(function ($item, $key) {
            return $item->menu_type == 'header';
        });
        $menu_childs = $menu->filter(function ($item, $key) use ($menu_header) {
            return $item->parent_id == $menu_header->id;
        });
    }
@endphp
<header class="header">
    <!-- Top Bar -->
    <div class="top_bar">
        <div class="top_bar_container">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="top_bar_content d-flex flex-row align-items-center justify-content-start">
                            <ul class="top_bar_contact_list">
                                <li>
                                    <div class="question">
                                        {{ $locale == $lang_default ? $setting->slogan : $setting->{$locale . '-slogan'} ?? '' }}
                                    </div>
                                </li>
                                <li>
                                    <div>
                                        {{ $locale == $lang_default ? $setting->phone : $setting->{$locale . '-phone'} ?? '' }}
                                    </div>
                                </li>
                                <li>
                                    <div>
                                        {{ $locale == $lang_default ? $setting->email : $setting->{$locale . '-email'} ?? '' }}
                                    </div>
                                </li>
                            </ul>
                            @if (isset($user_auth))
                                <div class="top_bar_login ml-auto">
                                    <div class="button">
                                        <a href="{{ route('frontend.user') }}" class="text-white">
                                            @lang('Thông tin tài khoản')
                                            <div class="button_arrow"><i class="fa fa-user-circle-o"
                                                    aria-hidden="true"></i>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            @else
                                <div class="top_bar_login ml-auto">
                                    <div class="button">
                                        <a href="{{ route('frontend.login') }}" class="text-white" data-toggle="modal"
                                            data-target="#loginModal">
                                            @lang('Đăng nhập')
                                            <div class="button_arrow"><i class="fa fa-sign-in" aria-hidden="true"></i>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Header Content -->
    <div class="header_container">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="header_content d-flex flex-row align-items-center justify-content-start">
                        <div class="logo_container">
                            <a href="{{ route('home') }}">
                                <div class="logo_content d-flex flex-row align-items-end justify-content-start">
                                    <div class="logo_img"><img src="{{ $setting->logo_header }}"
                                            alt="{{ $setting->site_title }}"></div>
                                    {{-- <div class="logo_text">learn</div> --}}
                                </div>
                            </a>
                        </div>

                        <nav class="main_nav_contaner ml-auto">
                            <ul class="main_nav">
                                @if (isset($menu_childs) && count($menu_childs) > 0)
                                    @foreach ($menu_childs as $val_menu1)
                                        <li
                                            class="{{ (parse_url(url()->full(), PHP_URL_PATH) == '' && $val_menu1->url_link == '/') || $val_menu1->url_link == parse_url(url()->full(), PHP_URL_PATH) ? 'active' : '' }}">
                                            <a
                                                href="{{ $val_menu1->url_link ?? 'javascript:void(0)' }}">{{ $val_menu1->json_params->name->$locale ?? $val_menu1->name }}</a>
                                        </li>
                                    @endforeach
                                @endif
                                @if (isset($user_auth))
                                    <li class="">
                                        <a href="{{ route('frontend.logout') }}">@lang('Đăng xuất')</a>
                                    </li>
                                @endif
                            </ul>
                            {{-- <div class="search_button"><i class="fa fa-search" aria-hidden="true"></i></div> --}}
                            <!-- Hamburger -->

                            <div class="hamburger menu_mm">
                                <i class="fa fa-bars menu_mm" aria-hidden="true"></i>
                            </div>
                        </nav>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Header Search Panel -->
    {{-- <div class="header_search_container">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="header_search_content d-flex flex-row align-items-center justify-content-end">
                        <form action="#" class="header_search_form">
                            <input type="search" class="search_input" placeholder="Search" required="required">
                            <button
                                class="header_search_button d-flex flex-column align-items-center justify-content-center">
                                <i class="fa fa-search" aria-hidden="true"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    <!-- Header Search Panel -->
    <div class="menu d-flex flex-column align-items-end justify-content-start text-right menu_mm trans_400">
        <div class="menu_close_container">
            <div class="menu_close">
                <div></div>
                <div></div>
            </div>
        </div>
        {{-- <div class="search">
            <form action="#" class="header_search_form menu_mm">
                <input type="search" class="search_input menu_mm" placeholder="Search" required="required">
                <button
                    class="header_search_button d-flex flex-column align-items-center justify-content-center menu_mm">
                    <i class="fa fa-search menu_mm" aria-hidden="true"></i>
                </button>
            </form>
        </div> --}}
        <nav class="menu_nav">
            <ul class="menu_mm">
                @if (isset($menu_childs) && count($menu_childs) > 0)

                    @foreach ($menu_childs as $val_menu1)
                        <li class="menu_mm">
                            <a
                                href="{{ $val_menu1->url_link ?? 'javascript:void(0)' }}">{{ $val_menu1->json_params->name->$locale ?? $val_menu1->name }}</a>
                        </li>
                    @endforeach
                @endif
                @if (isset($user_auth))
                    <li class="">
                        <a href="{{ route('frontend.logout') }}">@lang('Đăng xuất')</a>
                    </li>
                @endif
            </ul>
        </nav>
        <div class="menu_extra">
            <div class="menu_phone"><span
                    class="menu_title">@lang('Phone'):</span>{{ $locale == $lang_default ? $setting->phone : $setting->{$locale . '-phone'} ?? '' }}
            </div>
            <div class="menu_social">
                <span class="menu_title">@lang('follow us')</span>
                <ul>
                    @if (isset($setting->facebook_url) || isset($setting->{$locale . '-facebook_url'}))
                        <li><a
                                href="{{ $locale == $lang_default ? $setting->facebook_url : $setting->{$locale . '-facebook_url'} ?? '' }}"><i
                                    class="fa fa-facebook" aria-hidden="true"></i></a></li>
                    @endif
                    @if (isset($setting->youtube_url) || isset($setting->{$locale . '-youtube_url'}))
                        <li><a
                                href="{{ $locale == $lang_default ? $setting->youtube_url : $setting->{$locale . '-youtube_url'} ?? '' }}"><i
                                    class="fa fa-youtube" aria-hidden="true"></i></a></li>
                    @endif
                    @if (isset($setting->linkedin_url) || isset($setting->{$locale . '-linkedin_url'}))
                        <li><a
                                href="{{ $locale == $lang_default ? $setting->linkedin_url : $setting->{$locale . '-linkedin_url'} ?? '' }}"><i
                                    class="fa fa-linkedin" aria-hidden="true"></i></a></li>
                    @endif
                    @if (isset($setting->instagram_url) || isset($setting->{$locale . '-instagram_url'}))
                        <li><a
                                href="{{ $locale == $lang_default ? $setting->instagram_url : $setting->{$locale . '-instagram_url'} ?? '' }}"><i
                                    class="fa fa-instagram" aria-hidden="true"></i></a></li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</header>
