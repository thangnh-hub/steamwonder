@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection
@section('style')
    <style>
        .d-inline-block {
            display: inline-block;
        }

        .pd-0 {
            padding-left: 0px !important;
        }

        .modal-header {
            background: #3c8dbc;
            color: #fff;

        }

        textarea.form-control {
            min-width: 150px;
        }

        @media (max-width: 768px) {

            .table>tbody>tr>td,
            .table>tbody>tr>th,
            .table>tfoot>tr>td,
            .table>tfoot>tr>th,
            .table>thead>tr>td,
            .table>thead>tr>th {
                padding: 1px;
            }
        }
    </style>
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

        @if (isset($params['class_id']) && isset($params['from_date']) && isset($params['to_date']))
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">@lang('Danh sách nhận xét đánh giá học viên lớp') {{ $this_class->name }} - Giảng viên:
                        {{ $teacher->name ?? '' }}. Từ ngày {{ $params['from_date'] ?? '' }} đến
                        {{ $params['to_date'] ?? '' }}
                        @if (isset($params['admission_id']))
                            <p style="margin-top: 10px">Của CBTS : {{ $cbts_name }} </p>
                        @endif
                    </h3>

                    @if (count($rows) > 0)
                        <form class="pull-right d-inline-block" action="{{ route('generate_pdf') }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            @php
                                $data['from_date'] = $params['from_date'];
                                $data['to_date'] = $params['to_date'];
                                $data['admission_id'] = $params['admission_id'];
                                $data['this_class'] = $this_class;
                                $data['teacher'] = $teacher;
                                $data['rows'] = $rows;
                            @endphp
                            <input type="hidden" name="view" value="admin.pages.evaluations.pdf">
                            <input type="hidden" name="namePDF" value="NhanXet.pdf">
                            <input type="hidden" name="data" value="{{ json_encode($data) }}">
                            <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-file-pdf-o"></i>
                                @lang('Download nhận xét')</button>
                        </form>
                    @endif
                    <div class="pull-right d-inline-block" style="margin-right: 10px">
                        <a href="{{ route('evaluations.class.index', ['class_id' => $class->id ?? 0]) }}">
                            <button type="button" class="btn btn-warning btn-sm">@lang('Lịch sử nhận xét - đánh giá')</button>
                        </a>
                    </div>
                </div>
                <div class="box-body">
                    @if (count($rows) == 0)
                        <div class="alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            @lang('not_found')
                        </div>
                    @else
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th>@lang('Order')</th>
                                    <th>@lang('Mã học viên')</th>
                                    <th>@lang('Student')</th>
                                    <th>@lang('Học lực')</th>
                                    <th>@lang('Ý thức')</th>
                                    <th>@lang('Kiến thức')</th>
                                    <th>@lang('Kỹ năng')</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rows as $key => $row)
                                    <tr class="valign-middle">
                                        <td>
                                            {{ $loop->index + 1 }}
                                        </td>

                                        <td>{{ $row->student->admin_code ?? '' }}</td>
                                        <td>
                                            <a target="_blank" href="{{ route('students.show', $row->student->id) }}">{{ $row->student->name ?? '' }}
                                            </a>
                                        </td>
                                        <td>
                                            {!! nl2br($row->json_params->ability ?? '') !!}
                                        </td>
                                        <td>
                                            {!! nl2br($row->json_params->consciousness ?? '') !!}
                                        </td>
                                        <td>
                                            {!! nl2br($row->json_params->knowledge ?? '') !!}
                                        </td>
                                        <td>
                                            {!! nl2br($row->json_params->skill ?? '') !!}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        @endif
    </section>

@endsection
@section('script')
    <script></script>
@endsection
