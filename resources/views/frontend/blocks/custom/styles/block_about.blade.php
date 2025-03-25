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
    <section class="about-area section-gap">
        <div class="container">
            <div class="row align-items-center justify-content-center">
                <div class="col-lg-5 col-md-6 about-left">
                    <img class="img-fluid" src="{{$image}}" alt="Image">
                </div>
                <div class="offset-lg-1 col-lg-5 col-md-12 about-right">
                    <h1>
                        {!!$title!!}
                    </h1>
                    <div>
                        {!!$content!!}
                    </div>
                </div>
            </div>
        </div>
    </section>

@endif
