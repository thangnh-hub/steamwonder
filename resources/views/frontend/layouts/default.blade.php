<!DOCTYPE html>
<html lang="{{ $locale ?? 'vi' }}">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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

    @include('frontend.components.sticky.modal')
    @include('frontend.panels.scripts')
    @include('frontend.components.sticky.alert')
    {{-- Include scripts --}}
    {{-- Scripts custom each page --}}
    @stack('script')

</body>

</html>
