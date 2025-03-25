@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection

@section('content-header')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang($module_name)
            <form class="pull-right" action="{{ route('exam_session_user.export_exam_result') }}" method="get">
                <input type="hidden" name="keyword" value="{{ isset($params['keyword']) ? $params['keyword'] : '' }}">
                <input type="hidden" name="course_id" value="{{ isset($params['course_id']) ? $params['course_id'] : '' }}">
                <input type="hidden" name="class_id" value="{{ isset($params['class_id']) ? $params['class_id'] : '' }}">
                <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-file-excel-o"></i>
                    @lang('Export kết quả')</button>
            </form>
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
            <form action="{{ route('exam_session_user.examResult') }}" method="GET" id="form_fillter">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Keyword') </label>
                                <input type="text" class="form-control" name="keyword" placeholder="@lang('Tên học viên, mã học viên, CCCD')"
                                    value="{{ isset($params['keyword']) ? $params['keyword'] : '' }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Khóa')</label>
                                <select name="course_id" class=" form-control select2">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($course as $key => $val)
                                        <option value="{{ $val->id }}"
                                            {{ isset($params['course_id']) && $params['course_id'] == $val->id ? 'selected' : '' }}>
                                            @lang($val->name)</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Class')</label>
                                <select name="class_id" id="class_id" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($class as $key => $value)
                                        <option value="{{ $value->id }}"
                                            {{ isset($params['class_id']) && $value->id == $params['class_id'] ? 'selected' : '' }}>
                                            {{ __($value->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Sắp xếp kết quả')</label>
                                <select name="order_by" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    <option value="DESC"
                                        {{ isset($params['order_by']) && $params['order_by'] == 'DESC' ? 'selected' : '' }}>
                                        @lang('Cao->thấp')</option>
                                    <option value="ASC"
                                        {{ isset($params['order_by']) && $params['order_by'] == 'ASC' ? 'selected' : '' }}>
                                        @lang('Thấp->cao')</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Filter')</label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-sm mr-10">@lang('Submit')</button>
                                    <a class="btn btn-default btn-sm" href="{{ route('exam_session_user.examResult') }}">
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
            <div class="box-body table-responsive">
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
                                <th>@lang('Mã học viên')</th>
                                <th>@lang('Học viên')</th>
                                <th>@lang('CCCD')</th>
                                <th>@lang('Khóa')</th>
                                <th>@lang('Mã CBTS')</th>
                                {{-- <th>@lang('CB tuyển sinh')</th> --}}
                                <th>@lang('Kết quả Test IQ')</th>
                                <th>@lang('Kết quả Test ngôn ngữ')</th>
                                <th>@lang('Trung bình')</th>
                                <th>@lang('Xếp loại')</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($rows as $row)
                                @if ($row->student)
                                    @php
                                        $student = $row->student;

                                        $detail_admission = $admission->first(function ($item, $key) use ($student) {
                                            return $item->id == $student->admission_id;
                                        });

                                        $diem_tb = round($row->diem_iq * 0.3 + $row->diem_acceptance * 0.7, 2);
                                        $xep_loai = $diem_tb >= 65 ? 'Đạt' : 'Không đạt';
                                    @endphp
                                    <tr class="valign-middle">
                                        <td>
                                            <strong style="font-size: 14px">{{ $row->student->admin_code ?? '' }}</strong>
                                        </td>
                                        <td>
                                            {{ $row->student->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $row->student->json_params->cccd ?? '' }}
                                        </td>
                                        <td>
                                            {{ $row->course->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $detail_admission->admin_code ?? '' }}
                                        </td>
                                        {{-- <td>
                                            {{ $detail_admission->name ?? '' }}
                                        </td> --}}
                                        <td>
                                            <span class="txt_score">{{ $row->diem_iq }}</span>
                                        </td>
                                        <td>
                                            <span class="txt_score">{{ $row->diem_acceptance }}</span>
                                        </td>
                                        <td>
                                            {{ $diem_tb }}
                                        </td>
                                        <td>
                                            {{ $xep_loai }}
                                        </td>
                                    </tr>
                                @endif
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
