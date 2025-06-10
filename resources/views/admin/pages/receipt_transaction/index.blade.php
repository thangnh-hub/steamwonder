@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection
@section('content-header')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang($module_name)
        </h1>

    </section>
@endsection

@section('content')
    <!-- Main content -->
    <section class="content">
        {{-- Search form --}}
        <div class="box box-default">

            <div class="box-header with-border">
                <h3 class="box-title">@lang('Filter')</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <form action="{{ route(Request::segment(2) . '.index') }}" method="GET">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Mã, tên TBP') </label>
                                <input type="text" class="form-control" name="keyword" placeholder="@lang('Mã TBP hoặc tên TBP')"
                                    value="{{ isset($params['keyword']) ? $params['keyword'] : '' }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Mã, tên học sinh') </label>
                                <input type="text" class="form-control" name="keyword_student"
                                    placeholder="@lang('Mã học sinh hoặc tên học sinh')"
                                    value="{{ isset($params['keyword_student']) ? $params['keyword_student'] : '' }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Từ ngày')</label>
                                <input type="date" name="from_date" class="form-control" value="{{$params['from_date']}}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Đến ngày ngày')</label>
                                <input type="date" name="to_date" class="form-control" value="{{$params['to_date']}}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Filter')</label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-sm mr-10">@lang('Submit')</button>
                                    <a class="btn btn-default btn-sm" href="{{ route(Request::segment(2) . '.index') }}">
                                        @lang('Reset')
                                    </a>
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
                <h3 class="box-title">@lang('List')</h3>
            </div>
            <div class="box-body box_alert">
                @if (session('errorMessage'))
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        {{ session('errorMessage') }}
                    </div>
                @endif
                @if (session('successMessage'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        {{ session('successMessage') }}
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
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>@lang('Mã TBP')</th>
                                <th>@lang('Tên TBP')</th>
                                <th>@lang('Học sinh')</th>
                                <th>@lang('Ngày thanh toán')</th>
                                <th>@lang('Số tiền')</th>
                                <th>@lang('Ghi chú')</th>
                                <th>@lang('Người thu ')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $row)
                                <tr class="valign-middle">
                                    <td>
                                        <strong style="font-size: 14px">{{ $row->receipt->receipt_code ?? '' }}</strong>
                                    </td>
                                    <td>
                                        {{ $row->receipt->receipt_name ?? '' }}
                                    </td>
                                    <td>
                                        {{ $row->receipt->student->student_code ?? '' }} - {{ $row->receipt->student->first_name ?? '' }} {{ $row->receipt->student->last_name ?? '' }}
                                    </td>
                                    <td>
                                        {{ date('d-m-Y', strTotime($row->payment_date)) }}
                                    </td>
                                    <td>
                                        {{ number_format($row->paid_amount, 0, ',', '.') ?? '' }}
                                    </td>
                                    <td>
                                        {{ $row->json_params->note ?? '' }}

                                    </td>
                                    <td>
                                        {{ $row->user_cashier->name ?? '' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

            <div class="box-footer clearfix">
                <div class="row">
                    <div class="col-sm-5">
                        Tìm thấy {{ $rows->total() }} kết quả
                    </div>
                    <div class="col-sm-7">
                        {{ $rows->withQueryString()->links('admin.pagination.default') }}
                    </div>
                </div>
            </div>

        </div>
    </section>

@endsection
@section('script')
    <script></script>
@endsection
