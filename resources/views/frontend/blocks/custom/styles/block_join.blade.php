@if ($block)
    @php
        $title = $block->json_params->title->{$locale} ?? $block->title;
        $brief = $block->json_params->brief->{$locale} ?? $block->brief;
        $des = $block->json_params->des->{$locale} ?? '';
        $content = $block->json_params->content->{$locale} ?? $block->content;
        $image = $block->image != '' ? $block->image : null;
        $image_background = $block->image_background != '' ? $block->image_background : null;
        $url_link = $block->url_link != '' ? $block->url_link : '';
        $url_link_title = $block->json_params->url_link_title->{$locale} ?? $block->url_link_title;
        // Filter all blocks by parent_id
        $block_childs = $blocks->filter(function ($item, $key) use ($block) {
            return $item->parent_id == $block->id;
        });
    @endphp

    <div class="join py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 offset-lg-1">
                    <div class="section_title text-center">
                        <h2>{{ $title }}</h2>
                    </div>
                    <div class="section_subtitle">{{ $brief }}</div>
                </div>
            </div>
            {{-- <div class="row loaders_container">
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
                        <div class="col-lg-3 col-6 loader_col">
                            <!-- Loader -->
                            <div class="loader" data-perc="{{ (int) $brief_childs / 100 }}"><span>{{ $title_childs }}</span>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div> --}}
        </div>
        @if (!$user_auth && $url_link != '' && $url_link_title != '')
            <div class="button join_button"><a href="{{ $url_link }}" data-toggle="modal">{{ $url_link_title }}<div
                        class="button_arrow"><i class="fa fa-angle-right" aria-hidden="true"></i></div></a></div>
        @endif
    </div>
@endif
