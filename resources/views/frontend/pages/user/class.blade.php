@extends('frontend.layouts.default')

@section('content')
    <section class="student">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 information">
                    <div class="box box-warning mb-3">
                        <div class="box-header with-border mb-3">
                            <h3 class="box-title">
                                <i class="fa fa-graduation-cap"></i> @lang('Thông tin lớp học')
                            </h3>
                        </div>
                        <div class="box-body">
                            @foreach ($classs as $class)
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="col-sm-12">
                                            <p><strong>@lang('Mã lớp'): </strong>{{ $class->code ?? '' }}</p>
                                        </div>
                                        <div class="col-sm-12">
                                            <p><strong>@lang('Tên lớp'): </strong>{{ $class->name ?? 'Chưa cập nhật' }}</p>
                                        </div>
                                        <div class="col-sm-12">
                                            <p><strong>@lang('Hệ đào tạo'):
                                                </strong>{{ $class->education_programs->name ?? 'Chưa cập nhật' }}
                                            </p>
                                        </div>
                                        <div class="col-sm-12">
                                            <p><strong>@lang('Nhóm tuổi'):
                                                </strong>{{ $class->education_ages->name ?? 'Chưa cập nhật' }}</p>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="col-sm-12">
                                            <p><strong>@lang('Phòng học'):</strong>
                                                {{ $class->room->name ?? 'Chưa câp nhật' }}
                                            </p>
                                        </div>
                                        <div class="col-sm-12">
                                            <p><strong>@lang('Khu vực'):
                                                </strong>{{ $class->area->name ?? 'Chưa cập nhật' }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
