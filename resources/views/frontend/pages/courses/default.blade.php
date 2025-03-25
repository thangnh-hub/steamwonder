{{-- Check và gọi template tương ứng --}}
@extends('frontend.layouts.default')

@section('content')
    <div class="banner-breadcrums">
        <div class="breadcrums_background parallax_background parallax-window" data-parallax="scroll"
            data-image-src="{{ $setting->background_breadcrumbs }}" data-speed="0.8"></div>
        <div class="breadcrums_container">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="breadcrums_content">
                            <div class="breadcrums_title">@lang('Danh sách khóa học')</div>
                            <div class="breadcrumbs">
                                <ul>
                                    <li><a href="{{ route('home') }}">@lang('Home')</a></li>
                                    <li>@lang('Khóa học')</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="courses">
        <div class="container">

            @if (isset($rows) && count($rows) > 0)
                <div class="row courses_row mt-4">
                    @foreach ($rows as $items)
                        @php
                            $course_name = $items->name ?? '';
                            $course_brief = $items->json_params->brief ?? '';
                            $course_price = $items->json_params->price ?? 0;
                            $course_image = $items->json_params->image ?? url('themes/admin/img/no_image.jpg');
                            $course_bai_hoc = $items->lessons->count() ?? 0;
                            $course_thoi_luong = $items->json_params->thoi_luong ?? '---';
                            $alias = route('frontend.course.detail', Str::slug($course_name) . '-' . $items->id);
                        @endphp
                        <div class="col-lg-4 col-md-6">
                            <div class="course">
                                <div class="course_image"><img src="{{ $course_image }}" alt="{{ $course_name }}"></div>
                                <div class="course_body">
                                    <div class="course_header d-flex flex-row align-items-center justify-content-start">
                                        <div class="course_tag"></div>
                                        <div class="course_price ml-auto">
                                            <span>{{ $course_price > 0 ? number_format($course_price, 0, ',', '.') . '₫' : __('Chưa cập nhật') }}</span>
                                        </div>
                                    </div>
                                    <div class="course_title">
                                        <h3><a href="{{$alias}}">{{ $course_name }}</a></h3>
                                    </div>
                                    <div class="course_footer d-flex align-items-center justify-content-start">
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
                </div>
                {{ $rows->withQueryString()->links('frontend.pagination.default') }}
            @endif
        </div>
    </div>
@endsection
