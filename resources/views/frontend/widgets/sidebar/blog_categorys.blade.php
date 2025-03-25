@php
    $list_tag = $taxonomys->filter(function ($item, $key) {
        return $item->taxonomy == 'tag';
    });
    $list_post = $taxonomys->filter(function ($item, $key) {
        return $item->taxonomy == 'post' && $item->parent_id != null;
    });
@endphp
<div class="col-lg-4 sidebar-widgets">
    <div class="widget-wrap">
        <div class="single-sidebar-widget search-widget">
            <form class="search-form" action="">
                <input placeholder="Search Posts" name="search" type="text" onfocus="this.placeholder = ''"
                    onblur="this.placeholder = 'Search Posts'">
                <button type="submit"><i class="fa fa-search"></i></button>
            </form>
        </div>
        <div class="single-sidebar-widget popular-post-widget">
            <h4 class="popular-title">@lang('Bài viết xem nhiều')</h4>
            <div class="popular-post-list">
                @isset($visited_post)
                    @foreach ($visited_post as $items_blog)
                        @php
                            $titlel_blog = $items_blog->json_params->name->{$locale} ?? $items_blog->name;
                            $brief_blog = $items_blog->json_params->brief->{$locale} ?? $items_blog->brief;
                            $image_blog = $items_blog->image != '' ? $items_blog->image : 'data/images/no_image.jpg';
                            $time_blog = date('d, M, Y', strtotime($items_blog->updated_at));
                            $alias_blog = route('frontend.page', [
                                'taxonomy' => $items_blog->taxonomy_alias ?? '',
                                'alias' => $items_blog->alias ?? '',
                            ]);
                        @endphp
                        <div class="single-post-list d-flex flex-row align-items-center">
                            <div class="thumb">
                                <img class="img-fluid" src="{{ $image_blog }}" alt="{{ $titlel_blog }}">
                            </div>
                            <div class="details">
                                <a href="{{ $alias_blog }}">
                                    <h6>{{ $titlel_blog }}</h6>
                                </a>
                                <p>{{ $time_blog }}</p>
                            </div>
                        </div>
                    @endforeach
                @endisset
            </div>
        </div>


        <div class="single-sidebar-widget popular-post-widget">
            <h4 class="popular-title">@lang('Bài viết nổi bật')</h4>
            <div class="popular-post-list">
                @isset($featured_post)
                    @foreach ($featured_post as $items_blog)
                        @php
                            $titlel_blog = $items_blog->json_params->name->{$locale} ?? $items_blog->name;
                            $brief_blog = $items_blog->json_params->brief->{$locale} ?? $items_blog->brief;
                            $image_blog = $items_blog->image != '' ? $items_blog->image : 'data/images/no_image.jpg';
                            $time_blog = date('d, M, Y', strtotime($items_blog->updated_at));
                            $alias_blog = route('frontend.page', [
                                'taxonomy' => $items_blog->taxonomy_alias ?? '',
                                'alias' => $items_blog->alias ?? '',
                            ]);
                        @endphp
                        <div class="single-post-list d-flex flex-row align-items-center">
                            <div class="thumb">
                                <img class="img-fluid" src="{{ $image_blog }}" alt="{{ $titlel_blog }}">
                            </div>
                            <div class="details">
                                <a href="{{ $alias_blog }}">
                                    <h6>{{ $titlel_blog }}</h6>
                                </a>
                                <p>{{ $time_blog }}</p>
                            </div>
                        </div>
                    @endforeach
                @endisset
            </div>
        </div>

        <div class="single-sidebar-widget post-category-widget">
            <h4 class="category-title">@lang('Post Catgories')</h4>
            <ul class="cat-list">
                @if ($list_post)
                    @foreach ($list_post as $items)
                        @php
                            $post_name = $items->json_params->title->{$locale} ?? $items->name;
                            $post_count = $items->count_post ?? 0;
                            $post_alias = route('frontend.page', [
                                'taxonomy' => $items->alias ?? '',
                            ]);
                        @endphp
                        <li>
                            <a href="{{$post_alias}}" class="d-flex justify-content-between">
                                <p>{{$post_name}}</p>
                                <p>{{$post_count}}</p>
                            </a>
                        </li>
                    @endforeach
                @endif


            </ul>
        </div>

        <div class="single-sidebar-widget tag-cloud-widget">
            <h4 class="tagcloud-title">@lang('Tag Clouds')</h4>
            <ul>
                @if ($list_tag)
                    @foreach ($list_tag as $item_tag)
                        @php
                            $tag_name = $item_tag->json_params->title->{$locale} ?? $item_tag->name;
                            $tag_alias = route('frontend.tag', [
                                'alias' => $item_tag->alias ?? '',
                            ]);
                        @endphp
                        <li><a href="{{ $tag_alias }}">{{ $tag_name }}</a>
                        </li>
                    @endforeach
                @endif
            </ul>
        </div>
    </div>
</div>
