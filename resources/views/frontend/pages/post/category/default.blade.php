{{-- Check và gọi template tương ứng --}}
@extends('frontend.layouts.default')

@section('content')
    <style>
        .sidebar {
            position: sticky;
            top: 120px;
        }
    </style>
    @php
        $title = $page->json_params->name->{$locale} ?? $page->name;
        $brief = $page->json_params->brief->{$locale} ?? $page->brief;
        $image = $page->json_params->image != '' ? $page->json_params->image : $setting->background_breadcrumbs;

    @endphp
    <div class="banner-breadcrums">
        <div class="breadcrums_background parallax_background parallax-window" data-parallax="scroll"
            data-image-src="{{ $image }}" data-speed="0.8"></div>
        <div class="breadcrums_container">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="breadcrums_content">
                            <div class="breadcrums_title">{{ $brief }}</div>
                            <div class="breadcrumbs">
                                <ul>
                                    <li><a href="{{route('home')}}">@lang('Home')</a></li>
                                    <li>{{$title}}</li>
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
                    <div class="news_posts">
                        @if (isset($rows) && count($rows) > 0)
                            @foreach ($rows as $items)
                                @php
                                    $admin_name = $items->admin_name ?? '';
                                    $title_child = $items->json_params->name->{$locale} ?? $items->name;
                                    $brief_child = $items->json_params->brief->{$locale} ?? $items->brief;
                                    $content_child = $items->json_params->content->{$locale} ?? $items->content;
                                    $image_child = $items->image != '' ? $items->image : 'data/images/no_image.jpg';
                                    $time = date('d m, Y', strtotime($items->updated_at));
                                    $alias = route('frontend.page', [
                                        'taxonomy' => $items->taxonomy_alias ?? '',
                                        'alias' => $items->alias ?? '',
                                    ]);
                                    $view = $items->count_visited ?? 0;
                                    $comment = $items->count_comment ?? 0;

                                @endphp
                                <div class="news_post">
                                    <div class="news_post_image"><img src="{{ $image_child }}" alt="{{ $title_child }}">
                                    </div>
                                    <div class="news_post_body">
                                        <div class="news_post_date"><a href="#"
                                                onclick="event.preventDefault();">{{ $time }}</a></div>
                                        <div class="news_post_title"><a href="{{ $alias }}">{{ $title_child }}</a>
                                        </div>
                                        <div class="news_post_text">
                                            <p>{!! Str::limit($brief_child, 125) !!}</p>
                                        </div>
                                        <div class="news_post_link"><a href="{{ $alias }}">@lang('Read More')</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <!-- Pagination -->
                    {{ $rows->withQueryString()->links('frontend.pagination.default') }}
                </div>
                <!-- Sidebar -->
                <div class="col-lg-4">
                    <div class="sidebar">
                        <div class="sidebar_search">
                            <form action="" id="sidebar_search_form" class="sidebar_search_form">
                                <input type="text" name="keyword" class="sidebar_search_input" placeholder="Search"
                                    value="{{ $params['keyword'] ?? '' }}" required="required">
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
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
