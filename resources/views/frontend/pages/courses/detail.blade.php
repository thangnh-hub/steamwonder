{{-- Check và gọi template tương ứng --}}
@extends('frontend.layouts.lesson')

@section('content')
    @php
        $title = $detail->name ?? '';
        $image =
            isset($detail->json_params->image) && $detail->json_params->image != ''
                ? $detail->json_params->image
                : url('themes/admin/img/no_image.jpg');
        $brief = $detail->json_params->brief ?? '';
        $target = $detail->json_params->target ?? '';
        $des = $detail->json_params->des ?? '';
        $count_order = $detail->json_params->count_order ?? 0;
        $price =
            isset($detail->json_params->price) && $detail->json_params->price != ''
                ? number_format($detail->json_params->price, 0, ',', '.')
                : '';
        $slot = $detail->json_params->slot ?? '';
        $bai_hoc = $detail->lessons->count() ?? 0;
        $thoi_luong = $detail->json_params->thoi_luong ?? '---';

    @endphp
    <style>
        .percent_poid {
            color: #12db31;
            margin-right: 15px
        }

        .percent_poid i {
            margin-right: 3px
        }

        .li_item_lesson .title_lesson {
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        h4 a {
            font-family: 'Montserrat', sans-serif;
            color: #44425a;
            -webkit-font-smoothing: antialiased;
            -webkit-text-shadow: rgba(0, 0, 0, .01) 0 0 1px;
            text-shadow: rgba(0, 0, 0, .01) 0 0 1px;
        }

        @media (min-width: 576px) {
            .modal-dialog {
                max-width: 50%
            }
        }

        @media screen and (max-width: 767px) {
            .li_item_lesson .title_lesson {
                width: calc(100% - 65px);
                margin-bottom: 5px;
            }
        }
    </style>

    <div class="banner-breadcrums">
        <div class="breadcrums_background parallax_background parallax-window" data-parallax="scroll"
            data-image-src="{{ $setting->background_breadcrumbs }}" data-speed="0.8"></div>
        <div class="breadcrums_container">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="breadcrums_content">
                            <div class="breadcrums_title">{{ $title }}</div>
                            <div class="breadcrumbs">
                                <ul>
                                    <li><a href="{{ route('home') }}">@lang('Home')</a></li>
                                    <li><a href="{{ route('frontend.course.list') }}">@lang('Khóa học')</a></li>
                                    <li>{{ $title }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="course_detail">
        <div class="container">
            <div class="row">
                <!-- News Posts -->
                <div class="col-lg-8">
                    <div class="intro">
                        <h3>@lang('Nội dung khóa học')</h3>
                        <div class="accordions">
                            @isset($detail->lessons)
                                @foreach ($detail->lessons as $items)
                                    @php
                                        if (isset($user_auth)) {
                                            $point = App\Models\LessonUser::select('percent_point as point')
                                                ->where('lesson_id', $items->id)
                                                ->where('user_id', $user_auth->id)
                                                ->first();
                                        }
                                    @endphp
                                    <div class="accordion_container">
                                        <div class="accordion d-flex flex-row align-items-center justify-content-between">
                                            <h4>
                                                {{ Str::limit($items->title, 50) }}</h4>
                                            <div class="btn_detail d-flex align-items-center mr-5">
                                                @if (isset($user_auth))
                                                    @if (isset($point->point) && $point->point == 100)
                                                        <span class="percent_poid align-items-center"> <i class="fa fa-circle"
                                                                aria-hidden="true"></i>{{ $point->point ?? 0 }}%</span>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                        <div class="accordion_panel">
                                            @if (isset($items->content) && $items->content != '')
                                                <div class="intro-lesson">
                                                    <h5>@lang('Nội dung buổi học')</h5>
                                                    <p>{!! nl2br($items->content ?? '') !!}</p>
                                                </div>
                                            @endif
                                            @if (isset($items->target) && $items->target != '')
                                                <div class="intro-lesson">
                                                    <h5>@lang('Mục tiêu buổi học')</h5>
                                                    <p>{!! nl2br($items->target ?? '') !!}</p>
                                                </div>
                                            @endif
                                            @if (isset($items->teacher_mission) && $items->teacher_mission != '')
                                                <div class="intro-lesson">
                                                    <h5>@lang('Nhiệm vụ giảng viên')</h5>
                                                    <p>{!! nl2br($items->teacher_mission ?? '') !!}</p>
                                                </div>
                                            @endif
                                            @if (isset($items->student_mission) && $items->student_mission != '')
                                                <div class="intro-lesson">
                                                    <h5>@lang('Nhiệm vụ học viên')</h5>
                                                    <p>{!! nl2br($items->student_mission ?? '') !!}</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @endisset
                        </div>
                    </div>

                    {{-- <div class="requiment">
                        <h3 class="mb-3">Điều kiện tham gia</h3>
                        <p>
                            Hiểu và sử dụng các cụm từ cơ bản hàng ngày và các câu rất đơn giản nhằm đáp ứng các nhu
                            cầu cụ thể.
                            Giới thiệu bản thân và người khác, và có thể hỏi và trả lời các câu hỏi về thông tin cá
                            nhân như nơi sống, người quen và những gì họ có.
                            Giao tiếp một cách đơn giản nếu người đối thoại nói chậm và rõ ràng và sẵn sàng giúp đỡ.

                        </p>
                    </div> --}}
                    <div class="description mt-3">
                        <h3 class="mb-3">@lang('Mô tả khóa học')</h3>
                        {!! $brief !!}
                    </div>

                </div>


                <!-- Sidebar -->
                <div class="col-lg-4">
                    <div class="sidebar sticky-sidebar">
                        <div class="course">
                            <div class="course_image"><img src="{{ $image }}" alt="{{ $title }}"></div>
                            <div class="course_body">
                                <div class="course_header d-flex flex-row align-items-center justify-content-start">
                                    <div class="course_tag"></div>
                                    <div class="course_price ml-auto"><span>{{ $price }} đ</span></div>
                                </div>
                                <div class="course_footer d-flex align-items-center justify-content-start">
                                    <div class="mr-3">
                                        <i class="fa fa-file" aria-hidden="true"></i>
                                        <span class="ml-1">{{ $bai_hoc }} @lang('bài học')</span>
                                    </div>
                                    <div class="mr-3">
                                        <i class="fa fa-clock-o" aria-hidden="true"></i>
                                        <span class="ml-1">{{ $thoi_luong }}</span>
                                    </div>
                                    {{-- <div>
                                        <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                                        <span class="ml-1">352 sales</span>
                                    </div> --}}
                                </div>
                                <div class="enroll-course text-center">
                                    <div class="button">
                                        @isset($user_auth)
                                            @if ($order != null)
                                                @php
                                                    $arr_id_lesson_syllabus = $detail->lessons->pluck('id')->toArray();
                                                    // lấy thông tin lesson_user mới nhất của chương trình hiện tại xem đã học tới đâu
                                                    $lesson_user_detail = $lesson_user
                                                        ->filter(function ($item, $key) use ($arr_id_lesson_syllabus) {
                                                            return in_array(
                                                                $item->lesson_id,
                                                                $arr_id_lesson_syllabus,
                                                            ) &&
                                                                isset($item->json_params->tab_active) &&
                                                                count($item->json_params->tab_active) > 0;
                                                        })
                                                        ->sortByDesc('id')
                                                        ->first();
                                                    if ($lesson_user_detail) {
                                                        $tab = $lesson_user_detail->json_params->tab_active ?? [
                                                            'learning',
                                                        ];
                                                        $tab_active = end($tab);
                                                        $alias = $helpers::getRouteLessonDetail(
                                                            $detail->name,
                                                            $detail->id,
                                                            $lesson_user_detail->lesson_id,
                                                            $tab_active,
                                                        );
                                                    } else {
                                                        $alias = $helpers::getRouteLessonDetail(
                                                            $detail->name,
                                                            $detail->id,
                                                            $detail->lessons->first()->id,
                                                            'learning',
                                                        );
                                                    }
                                                @endphp
                                                <a href="{{ $alias }}" class="text-white">@lang('Vào học')
                                                    <div class="button_arrow"><i class="fa fa-angle-right"
                                                            aria-hidden="true"></i>
                                                    </div>
                                                </a>
                                            @else
                                                {{-- <a href="{{ route('frontend.order.courses', $detail->id) }}"
                                                    class="text-white">@lang('Đăng ký ngay')
                                                    <div class="button_arrow"><i class="fa fa-angle-right"
                                                            aria-hidden="true"></i>
                                                    </div>
                                                </a> --}}
                                                <a href="javascript:void(0)" class="text-white" data-toggle="modal"
                                                    data-target="#couserModal">@lang('Đăng ký ngay')
                                                    <div class="button_arrow"><i class="fa fa-angle-right"
                                                            aria-hidden="true"></i>
                                                    </div>
                                                </a>
                                            @endif
                                        @else
                                            <a href="javascript:void(0)" class="text-white" data-toggle="modal"
                                                data-target="#loginModal">@lang('Đăng ký ngay')
                                                <div class="button_arrow"><i class="fa fa-angle-right" aria-hidden="true"></i>
                                                </div>
                                            </a>
                                        @endisset
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>






@endsection
@push('script')
    <script></script>
@endpush
