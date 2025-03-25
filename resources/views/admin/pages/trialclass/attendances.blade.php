@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection


<style>
    .d-flex {
        display: flex
    }

    #alert-config {
        width: auto !important;
    }

    .input-with-suffix {
        position: relative;
    }

    .pointer-none {
        pointer-events: none;
    }

    .input-suffix {
        position: absolute;
        right: 30px;
        top: 8px;
    }
</style>

@section('content-header')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang($module_name)
        </h1>
    </section>
@endsection
@section('content')
    @php
        $teacher = \App\Models\Teacher::where('id', $this_class->json_params->teacher ?? 0)->first();
    @endphp
    <!-- Main content -->
    <section class="content">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">@lang('Lớp'):{{ $this_class->name }} - Giảng viên: {{ $teacher->name ?? '' }} -
                    Buổi: {{ optional(\Carbon\Carbon::parse($schedule->date))->format('l d/m/Y') }}</h3>
                @if (count($rows) > 0)
                    <form class="pull-right d-inline-block" action="{{ route('generate_pdf') }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        @php
                            $data['this_class'] = $this_class;
                            $data['teacher'] = $teacher;
                            $data['rows'] = $rows;
                            $data['status'] = $status;
                            $data['is_homework'] = $is_homework;
                            $data['schedule'] = $schedule;
                            $data['list_class'] = $list_class;
                            $data['students'] = $students;
                        @endphp
                        <input type="hidden" name="view" value="admin.pages.trialclass.pdf">
                        <input type="hidden" name="namePDF" value="Diem_danh.pdf">
                        <input type="hidden" name="data" value="{{ json_encode($data) }}">
                        <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-file-pdf-o"></i>
                            @lang('Download điểm danh')</button>
                    </form>
                @endif
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
                                <th>@lang('Order')</th>
                                <th>@lang('Class')</th>
                                <th>@lang('Student')</th>
                                <th>@lang('Home Work')</th>
                                <th>@lang('Updated at')</th>
                                <th>@lang('Status')</th>
                                <th style="width:200px">@lang('Note status')</th>
                                <th style="width:300px">@lang('Ghi chú nhận xét (GV nhập)')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $key => $row)
                                <tr class="valign-middle">
                                    <td>
                                        {{ $loop->index + 1 }}
                                    </td>
                                    <td>
                                        <a
                                            href="{{ route('classs.edit', $row->class->id) }}">{{ $row->class->name ?? '' }}</a>
                                    </td>
                                    <td>
                                        <a href="{{ route('students.edit', $row->student->id) }}">{{ $row->student->name ?? '' }}
                                            ({{ $row->student->admin_code ?? '' }})
                                        </a>
                                    </td>
                                    <td>
                                        {{ $row->is_homework != '' ? __($is_homework[$row->is_homework]) : 'Chưa cập nhật' }}
                                    </td>
                                    <td>
                                        {{ $row->updated_at }}
                                    </td>
                                    <td>
                                        {{ __($status[$row->status]) }}
                                    </td>
                                    <td>
                                        <div class="note-status">
                                            @if ($row->status == \App\Consts::ATTENDANCE_STATUS['attendant'])
                                                {{ $row->json_params->value ?? '' }}
                                            @elseif($row->status == \App\Consts::ATTENDANCE_STATUS['absent'])
                                                {{ $row->json_params->value != '' ? __($option_absent[$row->json_params->value]) : '' }}
                                            @else
                                                {{ $row->json_params->value ?? '' }} <span
                                                    class="input-suffix">(@lang('minute'))</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        {{ $row->note_teacher ?? '' }}
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </section>
@endsection
