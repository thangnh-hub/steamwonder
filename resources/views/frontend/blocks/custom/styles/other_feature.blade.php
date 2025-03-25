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
        $i = 0;
    @endphp
    <section class="other-feature-area">
        <div class="container">
            <div class="feature-inner row">
                <div class="col-lg-12">
                    <div class="section-title text-left">
                        <h2>
                            {!! $title !!}
                        </h2>
                        <p>
                            {{ $brief }}
                        </p>
                    </div>
                </div>
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
                            $cl = '';
                            if ($loop->index == 3 * $i + 1) {
                                $cl = 'mt--160';
                            }
                            if ($loop->index == 3 * $i + 2) {
                                $cl = 'mt--260';
                                $i++;
                            }
                        @endphp
                        <div class="col-lg-4 col-md-6 {{ $cl }}" data="{{$loop->index .'-'.(3 * $i + 2)}}">
                            <div class="other-feature-item">
                                <i class="{{ $icon_childs }}"></i>
                                <h4>{{ $title_childs }}</h4>
                                <div>
                                    <p>
                                        {{ $brief_childs }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </section>
@endif
