@extends('admin.layouts.app')

@section('title')
  @lang($module_name)
@endsection
@php
  if (Request::get('lang') == $languageDefault->lang_locale || Request::get('lang') == '') {
      $lang = $languageDefault->lang_locale;
  } else {
      $lang = Request::get('lang');
  }
@endphp
@push('style')
  <style>
    table {
      max-width: unset !important;
      min-width: 0px !important;
    }

    table .btn {
      width: 100%;
    }

    .input-with-suffix {
      position: relative;
    }

    .input-suffix {
      position: absolute;
      right: 30px;
      top: 8px;
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
@endpush
@section('content-header')
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      @lang($module_name)
    </h1>

  </section>
@endsection

@section('content')

  <section class="content">
    <div class="box box-default hide-print">
      <form action="" method="GET">
        <div class="box-body">
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>@lang('Class')</label>
                <select name="class_id" id="class_id" class="form-control select2" style="width: 100%;">
                  <option value="">@lang('Please select')</option>
                  @foreach ($list_class as $key => $value)
                    <option value="{{ $value->id }}"
                      {{ isset($params['class_id']) && $value->id == $params['class_id'] ? 'selected' : '' }}>
                      {{ __($value->name) }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>@lang('Cán bộ tuyển sinh')</label>
                <select name="admission_id" id="admission_id" class="form-control select2" style="width: 100%;">
                  <option value="">@lang('Please select')</option>
                  @foreach ($list_admission as $key => $value)
                    <option value="{{ $value->id }}"
                      {{ isset($params['admission_id']) && $value->id == $params['admission_id'] ? 'selected' : '' }}>
                      {{ __($value->name) }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="col-md-2">
              <div class="form-group">
                <label>@lang('Filter')</label>
                <div>
                  <button type="submit" class="btn btn-primary btn-sm mr-10">@lang('Submit')</button>
                </div>
              </div>
            </div>

          </div>
        </div>
      </form>
    </div>
    @if (isset($this_class))
      @php
        $teacher = \App\Models\Teacher::where('id', $this_class->json_params->teacher ?? 0)->first();
        $data['teacher'] = $teacher;
        $data['this_class'] = $this_class;
      @endphp
      <div class="box">
        <div class="box-header">
          <h3 class="box-title">
            @lang('Danh sách học viên')
            - Lớp {{ $this_class->name }}
            - Giáo viên {{ $teacher->name }}
          </h3>
          @if (count($rows) > 0)
            @php
              $data['rows'] = $rows;
              $data['admission_id'] = $params['admission_id'] ?? '';
            @endphp
            <div class="pull-right hide-print">
              <form action="{{ route('generate_pdf') }}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="view" value="admin.pages.staffadmissions.pdf">
                <input type="hidden" name="data" value="{{ json_encode($data) }}">
                <button type="submit" name="download" value="pdf" class="btn btn-sm btn-success"><i
                    class="fa fa-file-pdf-o"></i>
                  @lang('Download bảng điểm')</button>
              </form>
            </div>
          @endif
          <button id="printButton" onclick="window.print()"
            class="btn btn-primary btn-sm mb-2 pull-right mr-10 hide-print">@lang('In thông tin')</button>
        </div>
        <div class="box-body ">
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
            <table class="table table-hover table-bordered" style="min-width:0px !important;">
              <thead>
                <tr>
                  <th rowspan="2">#</th>
                  <th rowspan="2" style="width:50px">@lang('Mã')</th>
                  <th rowspan="2" style="width:150px">@lang('Student')</th>
                  <th rowspan="2" style="width:150px">@lang('Loại hợp đồng')</th>
                  <th rowspan="2" style="width:100px">@lang('Lớp')</th>
                  <th colspan="5" style="text-align: center; width: 250px">@lang('Điểm') </th>
                  <th rowspan="2">@lang('Nhận xét')</th>
                  <th rowspan="2" style="width: 100px">@lang('Xếp loại')</th>
                </tr>
                <tr>
                  <th style="width: 50px">@lang('Nghe') </th>
                  <th style="width: 50px">@lang('Nói') </th>
                  <th style="width: 50px">@lang('Đọc') </th>
                  <th style="width: 50px">@lang('Viết') </th>
                  <th style="width: 50px">@lang('TB')</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($rows as $row)
                  <tr class="valign-middle">
                    <td rowspan="{{ count($row->userClasses) > 0 ? count($row->userClasses) : 1 }}">
                      {{ $loop->index + 1 }}
                    </td>
                    <td rowspan="{{ count($row->userClasses) > 0 ? count($row->userClasses) : 1 }}">
                      {{ $row->student->admin_code ?? '' }}
                    </td>
                    <td rowspan="{{ count($row->userClasses) > 0 ? count($row->userClasses) : 1 }}">
                      <a target="_blank"
                        href="{{ route('students.show', $row->student->id) }}">{{ $row->student->name ?? '' }}
                      </a>
                    </td>
                    <td rowspan="{{ count($row->userClasses) > 0 ? count($row->userClasses) : 1 }}">
                      {{ isset($row->student->json_params->contract_type) && $row->student->json_params->contract_type != null ? $row->student->json_params->contract_type : __('Chưa cập nhật') }}
                    </td>

                    @forelse($row->userClasses as $userClass)
                      {!! $loop->index > 0 ? '<tr>' : '' !!}
                      <td class='{{ $this_class->id == $userClass->class->id ? 'bg-gray' : '' }}'>
                        {{ $userClass->class->name ?? '' }}
                        ({{__($userClass->status)}})
                      </td>
                      <td>{{ $userClass->score->score_listen ?? '' }}</td>
                      <td>{{ $userClass->score->score_speak ?? '' }}</td>
                      <td>{{ $userClass->score->score_read ?? '' }}</td>
                      <td>{{ $userClass->score->score_write ?? '' }}</td>
                      <td>{{ $userClass->score->json_params->score_average ?? '' }}</td>
                      <td>{{ $userClass->score->json_params->note ?? '' }}</td>
                      <td>
                        {{ isset($userClass->score->status) ? App\Consts::ranked_academic_total[$userClass->score->status] ?? $userClass->score->status : 'Chưa xác định' }}
                      </td>
                      {!! $loop->index > 0 ? '</tr>' : '' !!}
                    @empty
                      <td class="bg-gray">
                        {{ $this_class->name ?? '' }}
                      </td>
                      <td>
                        {{ $row->score_listen ?? '' }}
                      </td>
                      <td>
                        {{ $row->score_speak ?? '' }}
                      </td>
                      <td>
                        {{ $row->score_read ?? '' }}
                      </td>
                      <td>
                        {{ $row->score_write ?? '' }}
                      </td>
                      <td>
                        {{ $row->json_params->score_average ?? '0' }}
                      </td>
                      <td>
                        {{ $row->json_params->note ?? '' }}
                      </td>
                      <td>
                        {{ $row->status != '' ? App\Consts::ranked_academic_total[$row->status] ?? $row->status : 'Chưa xác định' }}
                      </td>
                    @endforelse
                  </tr>
                @endforeach
              </tbody>
            </table>
          @endif
        </div>

        <div class="box-footer clearfix">

        </div>

      </div>

    @endif

  </section>

@endsection
@section('script')
@endsection
