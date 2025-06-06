@php
    if (isset($menu)) {
        $menu_footer = $menu->filter(function ($item, $key) {
            return $item->menu_type == 'footer';
        });
    }
@endphp
<footer class="footer">
    <div class="container">
        <div class="row justify-content-between">

            <!-- About -->
            <div class="col-lg-3 footer_col">
                <div class="footer_about">
                    <div class="logo_container">
                        <a href="{{route('home')}}">
                            <div class="logo_content d-flex flex-row align-items-end justify-content-start">
                                <div class="logo_img"><img src="{{ $setting->logo_header }}" alt="{{ $setting->site_title }}"></div>
                            </div>
                        </a>
                    </div>
                    <div class="footer_about_text">
                        <p>{{ $locale == $lang_default ? $setting->footer_text : $setting->{$locale . '-footer_text_url'} ?? '' }}</p>
                    </div>
                    <div class="footer_social">
                        <ul>
                            @if (isset($setting->facebook_url) || isset($setting->{$locale . '-facebook_url'}))
                                <li><a href="{{ $locale == $lang_default ? $setting->facebook_url : $setting->{$locale . '-facebook_url'} ?? '' }}"
                                        rel="nofollow"><i class="fa fa-facebook"></i></a></li>
                            @endif
                            @if (isset($setting->instagram_url) || isset($setting->{$locale . '-instagram_url'}))
                                <li><a href="{{ $locale == $lang_default ? $setting->instagram_url : $setting->{$locale . '-instagram_url'} ?? '' }}"
                                        rel="nofollow"><i class="fa fa-instagram"></i></a></li>
                            @endif
                            @if (isset($setting->linkedin_url) || isset($setting->{$locale . '-linkedin_url'}))
                                <li><a href="{{ $locale == $lang_default ? $setting->linkedin_url : $setting->{$locale . '-linkedin_url'} ?? '' }}"
                                        rel="nofollow"><i class="fa fa-linkedin"></i></a></li>
                            @endif
                            @if (isset($setting->youtube_url) || isset($setting->{$locale . '-youtube_url'}))
                                <li><a href="{{ $locale == $lang_default ? $setting->youtube_url : $setting->{$locale . '-youtube_url'} ?? '' }}"
                                        rel="nofollow"><i class="fa fa-youtube"></i></a></li>
                            @endif
                        </ul>
                    </div>
                    <div class="copyright">
                        <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                        {{ $locale == $lang_default ? $setting->copyright : $setting->{$locale . '-copyright'} ?? '' }}
                    </div>
                </div>
            </div>
            @if ($menu_footer)
                @foreach ($menu_footer as $item_menu)
                    @php
                        $title = $item_menu->json_parrams->name->{$locale} ?? $item_menu->name;
                        $menu_childs = $menu->filter(function ($item, $key) use ($item_menu) {
                            return $item->parent_id == $item_menu->id;
                        });
                    @endphp
                    <div class="col-lg-3 col-6 footer_col">
                        <div class="footer_links">
                            <div class="footer_title">{{ $title }}</div>
                            <ul class="footer_list">
                                @if ($menu_childs)
                                    @foreach ($menu_childs as $item_child)
                                        @php
                                            $title_child =
                                                $item_child->json_parrams->name->{$locale} ?? $item_child->name;
                                            $url_link = $item_child->url_link ?? 'javascript:void(0)';
                                            $taget = $item_child->json_params->target ?? '';
                                        @endphp
                                        <li><a href="{{ $url_link }}"
                                                target="{{ $taget }}">{{ $title_child }}</a></li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                    </div>
                @endforeach
            @endif
            <div class="col-lg-3 footer_col">
                <div class="footer_contact">
                    <div class="footer_title">@lang('Contact Us')</div>
                    <div class="footer_contact_info">
                        <div class="footer_contact_item">
                            <div class="footer_contact_title">@lang('Address'):</div>
                            <div class="footer_contact_line">{{ $locale == $lang_default ? $setting->address : $setting->{$locale . '-address'} ?? '' }}</div>
                        </div>
                        <div class="footer_contact_item">
                            <div class="footer_contact_title">@lang('Phone'):</div>
                            <div class="footer_contact_line">{{ $locale == $lang_default ? $setting->phone : $setting->{$locale . '-phone'} ?? '' }}</div>
                        </div>
                        <div class="footer_contact_item">
                            <div class="footer_contact_title">@lang('Email'):</div>
                            <div class="footer_contact_line">{{ $locale == $lang_default ? $setting->email : $setting->{$locale . '-email'} ?? '' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
