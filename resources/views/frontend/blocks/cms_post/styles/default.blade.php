@if ($block)
    @php
        $title = $block->json_params->title->{$locale} ?? $block->title;
        $brief = $block->json_params->brief->{$locale} ?? $block->brief;
        $content = $block->json_params->content->{$locale} ?? $block->content;
        $background = $block->image_background != '' ? $block->image_background : url('assets/img/banner.jpg');
        $url_link = $block->url_link != '' ? $block->url_link : '';
        $url_link_title = $block->json_params->url_link_title->{$locale} ?? $block->url_link_title;

        $params['status'] = App\Consts::STATUS['active'];
        $params['is_featured'] = true;
        $params['is_type'] = App\Consts::TAXONOMY['post'];
        $rows = App\Models\CmsPost::getsqlCmsPost($params)
            ->limit(App\Consts::LIMIT_TAXONOMY['post'])
            ->get();
        $i = 0;
    @endphp

    <section class="blog-post-area section-gap">
        <div class="container-fluid">
            <div class="feature-inner row">
                <div class="col-lg-12">
                    <div class="section-title text-left">
                        <h2>
                            {{ $title }}
                        </h2>
                        <p>
                            {{ $brief }}
                        </p>
                    </div>
                </div>
                @foreach ($rows as $item)
                    @php
                        $title_child = $item->json_params->name->{$locale} ?? $item->name;
                        $brief_child = $item->json_params->brief->{$locale} ?? $item->brief;
                        $content_child = $item->json_params->content->{$locale} ?? $item->content;
                        $image_child = $item->image != '' ? $item->image : 'data/images/no_image.jpg';
                        $time = date('d M, Y', strtotime($item->updated_at));
                        $alias = route('frontend.page', [
                            'taxonomy' => $item->taxonomy_alias ?? '',
                            'alias' => $item->alias ?? '',
                        ]);
                        $cl = '';
                        if ($loop->index == 3 * $i + 1) {
                            $cl = 'mt--160';
                        }
                        if ($loop->index == 3 * $i + 2) {
                            $cl = 'mt--260';
                            $i++;
                        }
                    @endphp
                    <div class="col-lg-4 col-md-6 {{ $cl }}">
                        <div class="single-blog-post">
                            <img src="{{ $image_child }}" class="img-fluid" alt="{{ $title_child }}" />
                            <div class="overlay"></div>
                            <div class="top-text">
                                <p>{{ $time }}</p>
                                {{-- <p>121 likes</p>
                                <p>05 comments</p> --}}
                            </div>
                            <div class="text">
                                <h4 class="text-white">{{ $title_child }}</h4>
                                <div>
                                    <p>
                                        {{ Str::Limit($brief_child, 200) }}
                                    </p>
                                </div>
                                <a href="{{ $alias }}" class="primary-btn">
                                    @lang('View Details')
                                    <i class="fa fa-long-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
