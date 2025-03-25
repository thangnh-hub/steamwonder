{{-- Check và gọi template tương ứng --}}
@extends('frontend.layouts.default')

@section('content')
    @php
        $admin_name = $detail->admin_name ?? '';
        $title = $detail->json_params->name->{$locale} ?? $detail->name;
        $brief = $detail->json_params->brief->{$locale} ?? $detail->brief;
        $content = $detail->json_params->content->{$locale} ?? $detail->content;
        $image = $detail->image != '' ? $detail->image : 'data/images/no_image.jpg';
        $time = date('d m, Y', strtotime($detail->updated_at));
        $taxonomy_title = $taxonomy_detail->name ?? '';
        $taxonomy_alias = $taxonomy_detail->alias ?? '';
        $taxonomy_image =
            $taxonomy_detail->json_params->image != ''
                ? $taxonomy_detail->json_params->image
                : $setting->background_breadcrumbs;
        $alias = route('frontend.page', [
            'taxonomy' => $taxonomy_alias ?? '',
            'alias' => $detail->alias ?? '',
        ]);
    @endphp
    <style>
        .sidebar {
            position: sticky;
            top: 120px;
        }

        .latest_post_image img {
            height: 100%;
            object-fit: cover;
        }
    </style>
    <div class="banner-breadcrums">
        <div class="breadcrums_background parallax_background parallax-window" data-parallax="scroll"
            data-image-src="{{ $taxonomy_image }}" data-speed="0.8"></div>
        <div class="breadcrums_container">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="breadcrums_content">
                            <div class="breadcrums_title">{{ $title }}</div>
                            <div class="breadcrumbs">
                                <ul>
                                    <li><a href="{{ route('home') }}">@lang('Home')</a></li>
                                    <li><a href="{{ $taxonomy_alias }}">{{ $taxonomy_title }}</a></li>
                                    <li>{{ $title }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="news">
        <div class="container">
            <div class="row mt-4">
                <div class="col-lg-8">
                    <div class="news_detail">
                        {!! $content !!}

                    </div>
                </div>
                <!-- Sidebar -->
                <div class="col-lg-4 ">
                    <div class="sidebar">
                        <div class="sidebar_search">
                            <form
                                action="{{ route('frontend.page', [
                                    'taxonomy' => $taxonomy_alias ?? '',
                                ]) }}"
                                id="sidebar_search_form" class="sidebar_search_form">
                                <input type="text" name="keyword" class="sidebar_search_input" placeholder="Search"
                                    required="required">
                                <button class="sidebar_search_button"><i class="fa fa-search"
                                        aria-hidden="true"></i></button>
                            </form>
                        </div>
                        <div class="sidebar_categories">
                            <div class="sidebar_title">@lang('Danh mục nổi bật')</div>
                            <div class="sidebar_links">
                                <ul>
                                    @isset($feature_taxonomy)
                                        @foreach ($feature_taxonomy as $items)
                                            @php
                                                $title_taxonomy = $items->json_params->name->{$locale} ?? $items->name;
                                                $brief_taxonomy = $items->json_params->brief->{$locale} ?? '';
                                                $image_taxonomy = $items->json_params->image ?? '';
                                                $alias_taxonomy = route('frontend.page', [
                                                    'taxonomy' => $items->alias ?? '',
                                                ]);
                                            @endphp
                                            <li><a href="{{ $alias_taxonomy }}">{{ $title_taxonomy }}</a></li>
                                        @endforeach
                                    @endisset
                                </ul>
                            </div>
                        </div>
                        <div class="sidebar_latest_posts">
                            <div class="sidebar_title">@lang('Bài viết nổi bật')</div>
                            <div class="latest_posts">
                                @isset($featured_post)
                                    @foreach ($featured_post as $items_blog)
                                        @php
                                            $titlel_blog =
                                                $items_blog->json_params->name->{$locale} ?? $items_blog->name;
                                            $brief_blog =
                                                $items_blog->json_params->brief->{$locale} ?? $items_blog->brief;
                                            $image_blog =
                                                $items_blog->image != ''
                                                    ? $items_blog->image
                                                    : 'data/images/no_image.jpg';
                                            $time_blog = date('d, M, Y', strtotime($items_blog->updated_at));
                                            $alias_blog = route('frontend.page', [
                                                'taxonomy' => $items_blog->taxonomy_alias ?? '',
                                                'alias' => $items_blog->alias ?? '',
                                            ]);
                                        @endphp
                                        <div class="latest_post d-flex flex-row align-items-start justify-content-start">
                                            <div>
                                                <div class="latest_post_image"><img src="{{ $image_blog }}"
                                                        alt="{{ $titlel_blog }}"></div>
                                            </div>
                                            <div class="latest_post_body">
                                                <div class="latest_post_date">{{ $time_blog }}</div>
                                                <div class="latest_post_title"><a
                                                        href="{{ $alias_blog }}">{{ $titlel_blog }}</a></div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endisset
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
