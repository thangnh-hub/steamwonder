<!DOCTYPE html>
<html lang="{{ $locale ?? 'vi' }}">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        {{ $meta['seo_title'] }}
    </title>
    <link rel="icon" href="{{ $setting->favicon ?? '' }}" type="image/x-icon">
    {{-- Print SEO --}}
    <meta name="description" content="{{ $meta['seo_description'] }}" />
    <meta name="keywords" content="{{ $meta['seo_keyword'] }}" />
    <meta name="news_keywords" content="{{ $meta['seo_keyword'] }}" />
    <meta property="og:image" content="{{ env('APP_URL') . $meta['seo_image'] }}" />
    <meta property="og:title" content="{{ $meta['seo_title'] }}" />
    <meta property="og:description" content="{{ $meta['seo_description'] }}" />
    <meta property="og:url" content="{{ Request::fullUrl() }}" />
    {{-- End Print SEO --}}
    {{-- Include style for app --}}
    @include('frontend.panels.styles')
    {{-- Styles custom each page --}}
    @stack('style')
    <style>
        .default-header {
            position: relative;
            background: #7C32FF;
        }

        .default-header.header-scrolled {
            position: fixed;
        }

        .breadcrumb a {
            color: inherit;
        }

        .sidebar ul li {
            padding: 10px 0px
        }

        .sidebar ul li a {
            color: initial
        }
    </style>
</head>

<body>
    @if (\View::exists('frontend.widgets.header.default'))
        @include('frontend.widgets.header.default')
    @else
        {{ 'View: frontend.widgets.header.default  do not exists!' }}
    @endif

    <div class="my-courses">
        <div class="container">
            <div class="row">

                <!-- News Posts -->
                <div class="col-lg-8">
                    <div class="row courses_row">
                        @foreach ($rows as $items)
                            @php
                                $course_name = $items->syllabuss_name;
                                $course_price = $items->syllabuss_json_params->price ?? 0;
                                $course_image =
                                    $items->syllabuss_json_params->image ?? url('themes/admin/img/no_image.jpg');
                                $alias = route( 'frontend.course.detail', Str::slug($course_name) . '-' . $items->syllabus_id);
                                $time_created = date('d/m/Y', strtotime($items->created_at));
                                $total_lesson = (int) $items->lessons->count();
                                $arr_id_lesson_syllabus = $items->lessons->pluck('id')->toArray();
                                $common_lessons = array_intersect($arr_id_lesson, $arr_id_lesson_syllabus);

                                // lấy thông tin lesson_user mới nhất của chương trình hiện tại xem đã học tới đâu
                                $lesson_user_detail = $lesson_user->filter(function ($item, $key) use ($arr_id_lesson_syllabus) {
                                    return in_array($item->lesson_id, $arr_id_lesson_syllabus);
                                })->sortByDesc('id')->first();
                                if($lesson_user_detail){
                                    $tab = $lesson_user_detail->json_params->tab_active??['learning'];
                                    $tab_active = end($tab);
                                    $alias = $helpers::getRouteLessonDetail($course_name,$items->syllabus_id,$lesson_user_detail->lesson_id,$tab_active);
                                };

                            @endphp
                            <div class="col-lg-6 col-md-6">
                                <div class="course">
                                    <div class="course_image"><img src="{{ $course_image }}"
                                            alt="{{ $course_name }}"></div>
                                    <div class="course_body">
                                        <div class="course_title mt-0">
                                            <h3><a href="{{ $alias }}">{{ $course_name }}</a></h3>
                                        </div>
                                        <div class="course_text">@lang('Đăng ký học ngày') {{ $time_created }}</div>
                                        <div class="progress-bar mt-3"
                                            title="{{ (count($common_lessons) / $total_lesson) * 100 }}%">
                                            <div class="progress"
                                                style="width: {{ (count($common_lessons) / $total_lesson) * 100 }}%;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <!-- Course -->
                    </div>
                </div>

                <!-- Sidebar -->
                @include('frontend.components.sticky.sidebar')

            </div>

        </div>
    </div>



    @if (\View::exists('frontend.widgets.footer.default '))
        @include('frontend.widgets.footer.default ')
    @else
        {{ 'View: frontend.widgets.footer.default do not exists!' }}
    @endif

    {{-- Include scripts --}}
    @include('frontend.components.sticky.modal')
    @include('frontend.panels.scripts')
    @include('frontend.components.sticky.alert')
    {{-- Include scripts --}}
    {{-- Scripts custom each page --}}
    @stack('script')

</body>

</html>
