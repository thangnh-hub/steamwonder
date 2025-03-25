<div class="footer-bottom row align-items-center">
    <p class="footer-text m-0 col-lg-8 col-md-12">
        {{ $setting->copyright }}
    </p>
    <div class="col-lg-4 col-md-12 footer-social">
        @if (isset($setting->facebook_url) || isset($setting->{$locale . '-facebook_url'}))
            <a href="{{ $locale == $lang_default ? $setting->facebook_url : $setting->{$locale . '-facebook_url'} ?? '' }}"
                rel="nofollow"><i class="fa fa-facebook"></i></a>
        @endif
        @if (isset($setting->instagram_url) || isset($setting->{$locale . '-instagram_url'}))
            <a href="{{ $locale == $lang_default ? $setting->instagram_url : $setting->{$locale . '-instagram_url'} ?? '' }}"
                rel="nofollow"><i class="fa fa-instagram"></i></a>
        @endif
        @if (isset($setting->linkedin_url) || isset($setting->{$locale . '-linkedin_url'}))
            <a href="{{ $locale == $lang_default ? $setting->linkedin_url : $setting->{$locale . '-linkedin_url'} ?? '' }}"
                rel="nofollow"><i class="fa fa-linkedin"></i></a>
        @endif
        @if (isset($setting->youtube_url) || isset($setting->{$locale . '-youtube_url'}))
            <a href="{{ $locale == $lang_default ? $setting->youtube_url : $setting->{$locale . '-youtube_url'} ?? '' }}"
                rel="nofollow"><i class="fa fa-youtube"></i></a>
        @endif
    </div>
</div>
