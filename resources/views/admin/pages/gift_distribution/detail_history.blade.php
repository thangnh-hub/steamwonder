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

@section('content-header')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang($module_name)
            <a href="{{ route('gift_distribution.list_history') }}" class=" pull-right btn btn-success">@lang('Danh sách')</a>
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
                            <th>@lang('Khóa học')</th>
                            <th>@lang('Quà đã phát')</th>
                            <th>@lang('Ngày phát')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (isset($gift_distribution) && count($gift_distribution) > 0)
                            @foreach ($gift_distribution as $student_id => $gifts)
                                @php 
                                    $student = $gifts->first()->student;
                                @endphp
                                <tr>
                                    <td rowspan="{{ $gifts->count() }}">{{ $loop->index + 1 }}</td>
                                    <td rowspan="{{ $gifts->count() }}">{{ $student->admin_code ?? '' }}</td>
                                    <td rowspan="{{ $gifts->count() }}">{{ $student->name ?? '' }}</td>
                                    <td rowspan="{{ $gifts->count() }}">{{ $student->course->name ?? '' }}</td>
                
                                    @foreach ($gifts as $index => $gift)
                                        @if ($index > 0)
                                            <tr>
                                        @endif
                                            <td>{{ $gift->product->name }}</td>
                                            <td>{{ date('d-m-Y', strtotime($entry->day_deliver)) }}</td>
                                        </tr>
                                    @endforeach
                            @endforeach
                        @endif
                    </tbody>
                </table>
                
            </div>
        </div>
    </section>
@endsection
@section('script')
    
@endsection
