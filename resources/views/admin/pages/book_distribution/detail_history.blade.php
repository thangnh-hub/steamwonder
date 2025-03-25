@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection
@section('style')
    <style>
        th {
            text-align: center;
            vertical-align: middle !important;
        }

        .table>tbody>tr>td {
            text-align: center;
            vertical-align: inherit;
        }

        .box_sign {
            display: flex;
            justify-content: space-between;
            margin-top: 50px;
        }

        .show-print {
            display: none;
        }

        .signature-column {
            width: 250px;
            height: 50px;
        }

        @media print {
            .hide-print {
                display: none;
                /* Ẩn nút khi in */
            }

            .show-print {
                display: block;
            }
        }
    </style>
@endsection

@php
    if (Request::get('lang') == $languageDefault->lang_locale || Request::get('lang') == '') {
        $lang = $languageDefault->lang_locale;
    } else {
        $lang = Request::get('lang');
    }
@endphp
@section('content-header')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang($module_name)
            <a href="{{ route('book_distribution.list_history') }}" class=" pull-right btn btn-success">@lang('Danh sách')</a>
        </h1>
    </section>
@endsection

@section('content')
    <!-- Main content -->
    <section class="content">

        <div class="box">
            <div class="box_alert">
                @if (session('errorMessage'))
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        {!! session('errorMessage') !!}
                    </div>
                @endif
                @if (session('successMessage'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        {!! session('successMessage') !!}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>

                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach

                    </div>
                @endif
            </div>

            <div class="box-header">
                <h3 class="title text-center">DANH SÁCH PHÁT GIÁO TRÌNH - Lớp: {{ $class_names }} - {{ $product_names }} - GV: {{$teacher_name}}
                </h3>
                @if (in_array($admin_auth->id, (array) $arr_teacher) || $admin_auth->id == 1)
                    @if ($entry->confirmed == 'da_nhan')
                        <button class="btn btn-sm btn-success pull-right mr-10 hide-print"
                            data-url="{{ route('book_distribution.confirm_teacher') }}">
                            @lang('Đã nhận sách')</button>
                    @else
                        <button class="btn btn-sm btn-danger pull-right mr-10 hide-print btn_confirm"
                            data-url="{{ route('book_distribution.confirm_teacher') }}">
                            @lang('Xác nhận đã nhận sách')</button>
                    @endif
                @endif

                <button class="btn btn-sm btn-warning pull-right mr-10 hide-print" onclick="window.print()"><i
                        class="fa fa-print"></i>
                    @lang('In danh sách học viên')</button>
            </div>
            <div class="box-body box-alert">
                <table class="table table-hover table-bordered sticky">
                    <thead>
                        <tr>
                            <th>@lang('STT')</th>
                            <th>@lang('Mã HV')</th>
                            <th>@lang('Họ tên')</th>
                            <th>@lang('khóa học')</th>
                            <th>@lang('Lớp')</th>
                            <th>@lang('Trình độ')</th>
                            <th>@lang('Sách đã phát')</th>
                            <th>@lang('Ngày nhận sách')</th>
                            <th class="show-print" style="width: 250px">@lang('Ký nhận')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (isset($book_distribution) && count($book_distribution) > 0)
                            @foreach ($book_distribution as $val)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $val->student->admin_code ?? '' }}</td>
                                    <td>{{ $val->student->name ?? '' }}</td>
                                    <td>{{ $val->student->course->name ?? '' }}</td>
                                    <td>{{ $val->class->name }}</td>
                                    <td>{{ $val->level->name ?? '' }}</td>
                                    <td>{{ $val->product->name }}</td>
                                    <td>{{ date('d-m-Y', strtotime($entry->day_deliver)) }}</td>
                                    <td class="show-print signature-column"></td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
                <div class="show-print">
                    <div class="box_sign ">
                        <div class="col-xs-4 text-center">
                            @lang('Người giao')
                        </div>
                        <div class="col-xs-4 text-center">
                            @lang('Người Nhận')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $('.btn_confirm').click(function() {
                if (confirm('Bạn chắc chắn xác nhận đã nhận sách !')) {
                    $.ajax({
                        url: '{{ route('book_distribution.confirm_teacher') }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            id: '{{ $entry->id }}',
                        },
                        success: function(response) {
                            if (response.data != null) {
                                location.reload();
                            } else {
                                var _html = `<div class="alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            Bạn không có quyền thao tác chức năng này!
                            </div>`;
                                $('.box_alert').prepend(_html);
                                $('html, body').animate({
                                    scrollTop: $(".alert").offset().top
                                }, 1000);
                                setTimeout(function() {
                                    $('.alert').remove();
                                }, 3000);
                            }
                        },
                        error: function(response) {
                            let errors = response.responseJSON.message;
                            alert(errors);
                        }
                    });
                }

            })
        });
    </script>
@endsection
