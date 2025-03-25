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

        td {
            vertical-align: middle !important;
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
@endsection

@section('content')
    <div id="loading-notification" class="loading-notification">
        <p>@lang('Please wait')...</p>
    </div>
    <!-- Main content -->
    <section class="content">
        {{-- Search form --}}
        <div class="box box-default hide-print">
            <div class="box-header with-border">
                <h3 class="box-title">@lang('Filter')</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <form action="{{ route('book_distribution.list_book_distribution_student') }}" id="form_filter" method="GET">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Kỳ')</label>
                                <input type="month" class="form-control" name="period"
                                    value="{{ $params['period'] ?? '' }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Filter')</label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-sm mr-10">@lang('Submit')</button>
                                    <a class="btn btn-default btn-sm mr-10"
                                        href="{{ route('book_distribution.list_book_distribution_student') }}">
                                        @lang('Reset')
                                    </a>
                                    <button class="btn btn-sm btn-warning mr-10" onclick="window.print()"><i
                                            class="fa fa-print"></i>
                                        @lang('In danh sách')</button>
                                    <button type="button" class="btn btn-sm btn-success btn_export"
                                        data-url="{{ route('book_distribution.export_list_book_distribution_student') }}"><i
                                            class="fa fa-file-excel-o" aria-hidden="true"></i>
                                        @lang('Export')</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        {{-- End search form --}}

        <div class="box">
            <div class="box-header">
                <h3 class="box-title">@lang($module_name)</h3>
            </div>
            <div class="box-body box-alert">
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
                @if (count($rows) == 0)
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        @lang('not_found')
                    </div>
                @else
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
                                <th>@lang('Kỳ nhận sách')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $val)
                                <tr>
                                    <td class="text-center">{{ $loop->index + 1 }}</td>
                                    <td class="text-center">{{ $val->student->admin_code ?? '' }}</td>
                                    <td class="text-center">{{ $val->student->name ?? '' }}</td>
                                    <td class="text-center">{{ $val->student->course->name ?? '' }}</td>
                                    <td class="text-center">{{ $val->class->name }}</td>
                                    <td class="text-center">{{ $val->level->name ?? '' }}</td>
                                    <td class="text-center">{{ $val->product->name }}</td>
                                    <td class="text-center">
                                        {{ \Carbon\Carbon::parse($params['period'])->format('m/Y') ?? '' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
            <div class="box-footer clearfix hide-print">

            </div>
        </div>
    </section>

@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $('.btn_export').click(function() {
                var formData = $('#form_filter').serialize();
                var url = $(this).data('url');
                show_loading_notification()
                $.ajax({
                    url: url,
                    type: 'GET',
                    xhrFields: {
                        responseType: 'blob'
                    },
                    data: formData,
                    success: function(response) {
                        if (response) {
                            var a = document.createElement('a');
                            var url = window.URL.createObjectURL(response);
                            a.href = url;
                            a.download = 'Danh sách học viên đã nhận sách.xlsx';
                            document.body.append(a);
                            a.click();
                            a.remove();
                            window.URL.revokeObjectURL(url);
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
                        hide_loading_notification()
                    },
                    error: function(response) {
                        hide_loading_notification()
                        let errors = response.responseJSON.message;
                        alert(errors);
                    }
                });
            })
        });
    </script>
@endsection
