@extends('frontend.layouts.default')

@section('content')
    <section class="student">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 information">
                    <div class="box box-warning mb-3">
                        <div class="box-header with-border mb-3">
                            <h3 class="box-title">
                                <i class="fa fa-address-card"></i> @lang('Thông tin học sinh')
                            </h3>
                        </div>
                        <div class="box-body">
                            @foreach ($students as $student)
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="col-sm-12">
                                            <p>
                                                <strong>@lang('Họ và tên'): </strong>
                                                {{ $student->first_name ?? '' }}
                                                {{ $student->last_name ?? '' }}
                                            </p>
                                        </div>
                                        <div class="col-sm-12">
                                            <p><strong>@lang('Nickname'):
                                                </strong>{{ $student->nickname ?? 'Chưa cập nhật' }}
                                            </p>
                                        </div>
                                        <div class="col-sm-12">
                                            <p><strong>@lang('Mã HS'):
                                                </strong>{{ $student->student_code ?? 'Chưa cập nhật' }}
                                            </p>
                                        </div>
                                        <div class="col-sm-12">
                                            <p><strong>@lang('Giới tính'):
                                                </strong>{{ __($student->sex) ?? 'Chưa cập nhật' }}</p>
                                        </div>
                                        <div class="col-sm-12">
                                            <p><strong>@lang('Ngày sinh'):</strong>
                                                {{ $student->birthday != '' ? date('d/m/Y', strtotime($student->birthday)) : 'Chưa cập nhật' }}
                                            </p>
                                        </div>
                                        <div class="col-sm-12">
                                            <p><strong>@lang('Địa chỉ'):
                                                </strong>{{ $student->address ?? 'Chưa cập nhật' }}</p>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="col-sm-12">
                                            <p><strong>@lang('Mã lớp'): </strong>{{ $student->currentClass->code ?? '' }}
                                            </p>
                                        </div>
                                        <div class="col-sm-12">
                                            <p><strong>@lang('Tên lớp'):
                                                </strong>{{ $student->currentClass->name ?? 'Chưa cập nhật' }}</p>
                                        </div>
                                        <div class="col-sm-12">
                                            <p><strong>@lang('Hệ đào tạo'):
                                                </strong>{{ $student->currentClass->education_programs->name ?? 'Chưa cập nhật' }}
                                            </p>
                                        </div>
                                        <div class="col-sm-12">
                                            <p><strong>@lang('Ngày nhập học'):
                                                </strong>{{ $student->enrolled_at != '' ? date('d/m/Y', strtotime($student->enrolled_at)) : 'Chưa cập nhật' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 information">
                    <div class="box box-warning mb-3">
                        <div class="box-header with-border mb-3">
                            <h3 class="box-title">
                                <i class="fa fa-user-circle-o"></i> @lang('Thông tin phụ huynh')
                            </h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                @foreach ($parents as $parent)
                                    <div class="col-sm-6 row mb-3">
                                        <div class="col-sm-12">
                                            <p>
                                                <strong>{{ $parent['relationship']->title }}: </strong>
                                                {{ $parent['parent']->first_name ?? '' }}
                                                {{ $parent['parent']->last_name ?? '' }}
                                            </p>
                                        </div>
                                        <div class="col-sm-12">
                                            <p><strong>@lang('Phone'):
                                                </strong>{{ $parent['parent']->phone ?? 'Chưa cập nhật' }}
                                            </p>
                                        </div>
                                        <div class="col-sm-12">
                                            <p><strong>@lang('Email'):
                                                </strong>{{ $parent['parent']->email ?? 'Chưa cập nhật' }}
                                            </p>
                                        </div>
                                        <div class="col-sm-12">
                                            <p><strong>@lang('Address'):
                                                </strong>{{ __($parent['parent']->address) ?? 'Chưa cập nhật' }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
