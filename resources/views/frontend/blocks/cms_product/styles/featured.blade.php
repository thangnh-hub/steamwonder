@if ($block)
    @php
        $title = $block->json_params->title->{$locale} ?? $block->title;
        $brief = $block->json_params->brief->{$locale} ?? $block->brief;

        $params['is_featured'] = '1';
        $params['type'] = 'elearning';
        $rows = App\Models\Syllabus::getSqlSyllabus($params)->first();
        // dd($rows);
        if ($rows) {
            $name = $rows->name ?? '';
            $brief = $rows->json_params->brief ?? '';
            $price = $rows->json_params->price ?? 0;
            $image = $rows->json_params->image ?? url('themes/admin/img/no_image.jpg');
            $thoi_luong = $rows->json_params->thoi_luong ?? __('Chưa cập nhật');
            $bai_hoc = $rows->lessons->count() ?? 0;
            $alias = route('frontend.course.detail', Str::slug($name) . '-' . $rows->id);
        }

    @endphp
    <div class="featured">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="featured_container">
                        @if ($rows)
                            <div class="row">
                                <div class="col-lg-6 featured_col">
                                    <div class="featured_content">
                                        <div
                                            class="featured_header d-flex flex-row align-items-center justify-content-start">
                                            <div class="featured_tag">
                                                <a href="#"
                                                    onclick="event.preventDefault();">@lang('Nổi bật')</a>
                                            </div>
                                            <div class="featured_price ml-auto">
                                                <span>{{ $price > 0 ? number_format($price, 0, ',', '.') . '₫' : __('Chưa cập nhật') }}</span>
                                            </div>
                                        </div>
                                        <div class="featured_title">
                                            <h3><a href="{{ $alias }}">{{ $name }}</a></h3>
                                        </div>
                                        <div class="featured_text">{!! Str::limit($brief, 125) !!}</div>
                                        <div class="featured_footer d-flex align-items-center justify-content-start">
                                            <div class="mr-3">
                                                <i class="fa fa-file" aria-hidden="true"></i>
                                                <span class="ml-">{{ $bai_hoc}}
                                                    @lang('bài học')</span>
                                            </div>
                                            <div class="mr-3">
                                                <i class="fa fa-clock-o" aria-hidden="true"></i>
                                                <span class="ml-1">{{ $thoi_luong }}</span>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 featured_col">
                                    <div class="featured_background" style="background-image:url({{ $image }})">
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
