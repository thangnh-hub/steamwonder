<!DOCTYPE html>
<html lang="{{ $locale ?? 'vi' }}">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        {{ $meta['seo_title'] }}
    </title>
    <link rel="icon" href="{{ $setting->favicon ?? '' }}" type="image/x-icon">
    {{-- Print SEO --}}
    <link rel="canonical" href="{{ Request::fullUrl() }}" />
    <meta name="description" content="{{ $meta['seo_description'] }}" />
    <meta name="keywords" content="{{ $meta['seo_keyword'] }}" />
    <meta name="news_keywords" content="{{ $meta['seo_keyword'] }}" />
    <meta property="og:image" content="{{ env('APP_URL') . $meta['seo_image'] }}" />
    <meta property="og:title" content="{{ $meta['seo_title'] }}" />
    <meta property="og:description" content="{{ $meta['seo_description'] }}" />
    <meta property="og:url" content="{{ Request::fullUrl() }}" />
    {{-- End Print SEO --}}
    {{-- Include style for app --}}
    @include('frontend.panels.styles')
    {{-- Styles custom each page --}}
    @stack('style')

    @stack('schema')
    <style>
        .default-header {
            position: relative;
            background: #7C32FF;
        }

        .default-header.header-scrolled {
            position: fixed;
        }
    </style>
</head>

<body>

    @if (\View::exists('frontend.widgets.header.default'))
        @include('frontend.widgets.header.default')
    @else
        {{ 'View: frontend.widgets.header.default  do not exists!' }}
    @endif

    @if (isset($blocks_selected))
        @foreach ($blocks_selected as $block)
            @if (\View::exists('frontend.blocks.' . $block->block_code . '.index'))
                @include('frontend.blocks.' . $block->block_code . '.index')
            @else
                {{ 'View: frontend.blocks.' . $block->block_code . '.index do not exists!' }}
            @endif
        @endforeach
    @endif

    @if (\View::exists('frontend.widgets.footer.default '))
        @include('frontend.widgets.footer.default ')
    @else
        {{ 'View: frontend.widgets.footer.default do not exists!' }}
    @endif
    <div class="modal fade" id="couserModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header border-bottom-0">
                    <h3 class="title">{{ $title ?? '' }}</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="d-flex flex-column-reverse flex-md-row justify-content-between">
                        <div class="content">
                            <p class="font-weight-bold">Mức giá: {{ $price ?? '' }} đ</p>
                            <p class="font-weight-bold">Thời gian: {{ $thoi_luong ?? '' }}</p>
                            <p class="font-weight-bold">Số bài học: {{ $bai_hoc ?? '' }}</p>
                        </div>
                        <div class="img ">
                            <img class="w-100" src="{{ $image }}" alt="{{ $title }}">
                        </div>

                    </div>
                    <div class="intro">
                        <h4>@lang('Nội dung khóa học')</h4>
                        <div class="accordions">
                            @isset($detail->lessons)
                                @foreach ($detail->lessons as $items)
                                    <div class="accordion_container">
                                        <div class="accordion d-flex flex-row align-items-center justify-content-between">
                                            <h4>
                                                {{ Str::limit($items->title, 50) }}</h4>
                                        </div>
                                    </div>
                                @endforeach
                            @endisset
                        </div>
                    </div>
                    <div class="description mt-3">
                        <h4 class="mb-3">@lang('Mô tả khóa học')</h4>
                        {!! $brief ?? '' !!}
                    </div>

                    <div class="modal-footer d-flex justify-content-center">
                        <div class="button">
                            <a href="{{ route('frontend.order.courses', $detail->id) }}"
                                onclick="return confirm('Bạn chắc chắn muốn đăng ký khóa học này?');">@lang('Đăng ký ngay')
                                <div class="button_arrow"><i class="fa fa-angle-right" aria-hidden="true"></i>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('frontend.components.sticky.modal')
    @include('frontend.panels.scripts')
    @include('frontend.components.sticky.alert')

    {{-- Include scripts --}}
    {{-- Scripts custom each page --}}
    @stack('script')

</body>

</html>
