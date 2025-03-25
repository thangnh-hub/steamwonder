@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection
@section('content-header')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang($module_name)
            {{-- <a class="btn btn-sm btn-warning pull-right" href="#"><i
                    class="fa fa-plus"></i> @lang('Add')</a> --}}
        </h1>
    </section>
@endsection

@section('content')

    <!-- Main content -->
    <section class="content">
        {{-- Search form --}}
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">{{ isset($this_class) ? 'Thông tin lớp ' . $this_class->name : 'Thông tin lớp' }}</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>

            <div class="d-flex-wap box-header">
                <div class="col-md-3">
                    <div class="form-group">
                        <label><strong>Sĩ số: </strong></label>
                        <span>{{ count($rows) }}</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label><strong>Trình độ: </strong></label>
                        <span>{{ $this_class->level->name ?? '' }}</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label><strong>Chương trình: </strong></label>
                        <span>{{ $this_class->syllabus->name ?? '' }}</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label><strong>Khóa học: </strong></label>
                        <span>{{ $this_class->course->name ?? '' }}</span>
                    </div>
                </div>
                
            </div>

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
                                <th>@lang('Order')</th>
                                <th>@lang('Student code')</th>
                                <th>@lang('Full name')</th>
                                <th>@lang('Gender')</th>
                                <th>@lang('State')</th>
                                <th>@lang('Updated at')</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $row)
                                @php
                                    $staff_row = $staffs->first(function ($item, $key) use ($row) {
                                        return $item->id == $row->admission_id;
                                    });
                                @endphp

                                <tr class="valign-middle">
                                    <td>{{ $loop->index + 1 }}</td>
                                    {{-- <td>
                                        {{ $row->email }}
                                    </td> --}}
                                    <td>
                                        <a target="_blank" class="btn btn-sm" data-toggle="tooltip" title="@lang('Detail')"
                                            data-original-title="@lang('Detail')"
                                            href="{{ route('students.show', $row->id) }}">
                                            {{ $row->admin_code }}
                                        </a>
                                    </td>
                                    <td>
                                        {{ $row->name ?? '' }}
                                    </td>
                                    <td>
                                        @lang($row->gender)
                                    </td>

                                    {{-- <td>
                                        @if ($staff_row)
                                            <a
                                                href="{{ route('staffs.edit', $staff_row->id ?? 0) }}">{{ $staff_row->name ?? '' }}</a>
                                        @endif
                                    </td> --}}
                                    <td>
                                        {{ $row->StatusStudent->name ?? '' }}
                                    </td>
                                    <td>
                                        {{ $row->updated_at }}
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
@section('script')
@endsection
