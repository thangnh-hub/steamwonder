@if ($block)
    @php
        $title = $block->json_params->title->{$locale} ?? $block->title;
        $brief = $block->json_params->brief->{$locale} ?? $block->brief;
        $des = $block->json_params->des->{$locale} ?? '';
        $content = $block->json_params->content->{$locale} ?? $block->content;
        $image = $block->image != '' ? $block->image : null;
        $image_background = $block->image_background != '' ? $block->image_background : null;
        // Filter all blocks by parent_id
        $block_childs = $blocks->filter(function ($item, $key) use ($block) {
            return $item->parent_id == $block->id;
        });
    @endphp

    @if ($image_background != null)
        <style>
            .video-area {
                background: url({{ $image_background }}) no-repeat center;
            }
            @media (max-width: 991px) {
                .video-area {
                    background: #828bb2;
                }
            }
        </style>
    @endif
    <section class="video-area section-gap-bottom">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-5">
                    <div class="section-title text-white">
                        <h2 class="text-white">
                            {!! $title !!}
                        </h2>
                        <p>
                            {{ $brief }}
                        </p>
                    </div>
                </div>
                <div class="offset-lg-1 col-md-6 video-left">
                    <div class="owl-carousel video-carousel">
                        @if ($block_childs)
                            @foreach ($block_childs as $item)
                                @php
                                    $title_childs = $item->json_params->title->{$locale} ?? $item->title;
                                    $brief_childs = $item->json_params->brief->{$locale} ?? $item->brief;
                                    $des_childs = $item->json_params->des->{$locale} ?? '';
                                    $iframe = $item->json_params->iframe->{$locale} ?? '';
                                    $image_childs = $item->image != '' ? $item->image : null;
                                    $image_background_childs =
                                        $item->image_background != '' ? $item->image_background : null;
                                    $url_link_childs = $item->url_link != '' ? $item->url_link : '';
                                    $url_link_title_childs =
                                        $item->json_params->url_link_title->{$locale} ?? $item->url_link_title;
                                    $icon_childs = $item->icon ?? '';
                                @endphp
                                <div class="single-video">
                                    <div class="video-part">
                                        <img class="img-fluid" src="{{ $image_childs }}" alt="Images">
                                        <div class="overlay"></div>
                                        <a class="popup-youtube play-btn" href="{{ $iframe }}">
                                            <img class="play-icon"
                                                src="{{ asset('themes/frontend/dwn/img/play-btn.png') }}"
                                                alt="Play">
                                        </a>
                                    </div>
                                    <h4 class="text-white mb-20 mt-30">{{ $title_childs }}</h4>
                                    <p class="text-white mb-20">
                                        {{ $brief_childs }}
                                    </p>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
