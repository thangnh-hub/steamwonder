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
    <section class="testimonials-area section-gap">
        <div class="container">
            <div class="testi-slider owl-carousel" data-slider-id="1">
                @if ($block_childs)
                    @foreach ($block_childs as $item)
                        @php
                            $title_childs = $item->json_params->title->{$locale} ?? $item->title;
                            $brief_childs = $item->json_params->brief->{$locale} ?? $item->brief;
                            $des_childs = $item->json_params->des->{$locale} ?? '';
                            $content_childs = $item->json_params->content->{$locale} ?? $item->content;
                            $image_childs = $item->image != '' ? $item->image : null;
                            $image_background_childs = $item->image_background != '' ? $item->image_background : null;
                            $url_link_childs = $item->url_link != '' ? $item->url_link : '';
                            $url_link_title_childs =
                                $item->json_params->url_link_title->{$locale} ?? $item->url_link_title;
                            $icon_childs = $item->icon ?? '';
                        @endphp
                        <div class="row align-items-center">
                            <div class="col-lg-5">
                                <div class="item">
                                    <div class="testi-item">
                                        <img src="{{ asset('themes/frontend/dwn/img/quote.png') }}" alt="Image" />
                                        <div class="mt-40 text">
                                            {!! $content_childs !!}
                                        </div>
                                        <h4>{{ $title_childs }}</h4>
                                        <p>{{ $brief_childs }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="offset-lg-1 col-lg-6">
                                <img src="{{ $image_childs }}" alt="{{ $title_childs }}" />
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </section>
@endif
