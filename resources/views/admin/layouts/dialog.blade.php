@yield('style')
@stack('style')
<div class="wrapper">
    <div class="content-wrapper">
        {{-- Header in content --}}
        @yield('content-header')
        {{-- Content detail --}}
        @yield('content')
    </div>
</div>
@yield('script')
@stack('script')
