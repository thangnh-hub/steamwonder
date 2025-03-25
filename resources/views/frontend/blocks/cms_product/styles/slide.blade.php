@if ($block)
    @php
        $title = $block->json_params->title->{$locale} ?? $block->title;
        $brief = $block->json_params->brief->{$locale} ?? $block->brief;
        $params['is_featured'] = '1';
        $params['type'] = 'elearning';
        $rows = App\Models\Syllabus::getSqlSyllabus($params)
            ->limit(App\Consts::PAGINATE['product'])
            ->get();
    @endphp

    <div class="featured-courses pt-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section_title d-flex">
                        <h2>{{ $title }}</h2>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="courses_slider_container mt-3">
                        <div class="owl-carousel owl-theme courses_slider">
                            @if (count($rows) > 0)
                                @foreach ($rows as $items)
                                    @php
                                        $course_name =$items->name??'';
                                        $course_brief = $items->json_params->brief ?? '';
                                        $course_price = $items->json_params->price ?? 0;
                                        $course_image = $items->json_params->image ?? url('themes/admin/img/no_image.jpg');
                                        $course_bai_hoc = $items->lessons->count() ?? 0;
                                        $course_thoi_luong = $items->json_params->thoi_luong ?? __('Chưa cập nhật');
                                        $alias = route(
                                            'frontend.course.detail',
                                            Str::slug($course_name) . '-' . $items->id,
                                        );
                                    @endphp
                                    <div class="owl-item">
                                        <div class="course">
                                            <div class="course_image"><img src="{{ $course_image }}"
                                                    alt="{{ $course_name }}">
                                            </div>
                                            <div class="course_body">
                                                <div
                                                    class="course_header d-flex flex-row align-items-center justify-content-start">
                                                    <div class="featured_tag">
                                                        <a href="#" onclick="event.preventDefault();">@lang('Nổi bật')</a>
                                                    </div>
                                                    <div class="course_price ml-auto">
                                                        <span>{{ $course_price > 0 ? number_format($course_price, 0, ',', '.') . '₫' : __('Chưa cập nhật') }}</span>
                                                    </div>
                                                </div>
                                                <div class="course_title">
                                                    <h3><a href="{{ $alias }}">{{ $course_name }}</a></h3>
                                                </div>
                                                <div
                                                    class="course_footer d-flex align-items-center justify-content-start">
                                                    <div class="mr-3">
                                                        <i class="fa fa-file" aria-hidden="true"></i>
                                                        <span class="ml-1">{{ $course_bai_hoc }} @lang('bài học')</span>
                                                    </div>
                                                    <div class="mr-3">
                                                        <i class="fa fa-clock-o" aria-hidden="true"></i>
                                                        <span class="ml-1">{{ $course_thoi_luong }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <div class="courses_slider_nav courses_slider_prev trans_200"><i class="fa fa-angle-left"
                                aria-hidden="true"></i></div>
                        <div class="courses_slider_nav courses_slider_next trans_200"><i class="fa fa-angle-right"
                                aria-hidden="true"></i></div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
