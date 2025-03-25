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
    @include('frontend.panels.styles_lesson')
    {{-- Styles custom each page --}}

    @stack('style')
    @stack('schema')

</head>

<body>

    @if (isset($blocks_selected))
        @foreach ($blocks_selected as $block)
            @if (\View::exists('frontend.blocks.' . $block->block_code . '.index'))
                @include('frontend.blocks.' . $block->block_code . '.index')
            @else
                {{ 'View: frontend.blocks.' . $block->block_code . '.index do not exists!' }}
            @endif
        @endforeach
    @endif
    <div class="modal fade" id="vocabularyModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="modal-body">

                    <div class="content"></div>
                </div>
                {{-- <div class="modal-footer d-flex justify-content-center">
                </div> --}}
            </div>
        </div>
    </div>
    {{-- @include('frontend.components.sticky.modal') --}}
    {{-- @include('frontend.panels.scripts') --}}
    <script src="{{ asset('themes/frontend/dwn/js/vendor/jquery-2.2.4.min.js') }}"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
    <script src="{{ asset('themes/frontend/education/plugins/scrollmagic/ScrollMagic.min.js') }}"></script>
    <script src="{{ asset('themes/frontend/education/plugins/progressbar/progressbar.min.js') }}"></script>
    <script src="{{ asset('themes/frontend/dwn/js/sweetalert2.all.min.js') }}"></script>
    @include('frontend.components.sticky.alert')
    {{-- Include scripts --}}
    {{-- Scripts custom each page --}}
    @stack('script')

</body>

</html>
