<div class="box">
    <div class="box-header">
        <h3 class="box-title">@lang('List')</h3>
        <button data-toggle="modal" data-target="#import_excel" type="button" class="btn btn-warning btn-sm pull-right">
            <i class="fa fa-file-excel-o" ></i> @lang('Nhập bằng excel')</button>
        <form class="mr-1 pull-right"  action="{{ route('export.studentdept.version1') }}" method="get"
            enctype="multipart/form-data">
            <input type="hidden" name="version" value="{{ isset($params['version']) ? $params['version'] : '' }}">
            <input type="hidden" name="keyword" value="{{ isset($params['keyword']) ? $params['keyword'] : '' }}">
            <input type="hidden" name="course_id" value="{{ isset($params['course_id']) ? $params['course_id'] : 0 }}">
            <input type="hidden" name="class_id" value="{{ isset($params['class_id']) ? $params['class_id'] : 0 }}">
            <input type="hidden" name="area_id" value="{{ isset($params['area_id']) ? $params['area_id'] : 0 }}">
            <input type="hidden" name="ketoan_xacnhan" value="{{ isset($params['ketoan_xacnhan']) ? $params['ketoan_xacnhan'] : 0 }}">
            <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-file-excel-o"></i>
                @lang('Export công nợ')</button>
        </form>    
    </div>
    <div class="box-body table-responsive">
        
        @if (count($rows) == 0)
            <div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                @lang('not_found')
            </div>
        @else
            <table class="table  table-bordered">
                <thead>
                    <tr>
                        <th rowspan="2">@lang('Order')</th>
                        <th rowspan="2">@lang('Student code')</th>
                        <th rowspan="2">@lang('Full name')</th>
                        {{-- <th rowspan="2">@lang('CCCD')</th> --}}
                        <th rowspan="2">@lang('Gender')</th>
                        <th rowspan="2">@lang('Loại hợp đồng')</th>
                        <th rowspan="2">@lang('Area')</th>
                        <th rowspan="2">@lang('Class')</th>
                        <th rowspan="2">@lang('Level')</th>
                        <th rowspan="2">@lang('Course')</th>
                        <th rowspan="2">@lang('Status Study')</th>

                        <th colspan="3">@lang('Số buổi học')</th>
                        <th rowspan="2">@lang('Xác nhận')</th>
                    </tr>
                    <tr>
                        <th style="width: 120px">@lang('Đã học')</th>
                        <th style="width: 120px">@lang('Thực tế')</th>
                        <th style="width: 120px">@lang('Còn lại')</th>
                    </tr>

                </thead>
                <tbody>
                    @foreach ($rows as $row)
                        <form action="" method="POST"
                            onsubmit="return confirm('@lang('confirm_action')')">
                            <tr class="valign-middle">
                                <td>{{ $loop->index + 1 }}</td>

                                <td>
                                    <a target="_blank" class="btn btn-sm" data-toggle="tooltip"
                                        title="@lang('Xem chi tiết')" data-original-title="@lang('Xem chi tiết')"
                                        href="{{ route('students.show', $row->user->id) }}">
                                        {{ $row->user->admin_code }}
                                    </a>
                                </td>
                                <td>
                                    {{ $row->user->name ?? '' }}
                                </td>
                                {{-- <td>
                                    {{ $row->user->json_params->cccd ?? '' }}
                                </td> --}}
                                <td>
                                    @lang($row->user->gender??"")
                                </td>
                                <td>
                                    {{ $row->user->json_params->contract_type??"" }}
                                </td>
                                <td>
                                    {{ $row->area_name ??""}}
                                </td>
                                
                                <td>
                                    {{ $row->class->name ??""}}
                                </td>
                                <td>
                                    {{ $row->class->level->name ??"" }}
                                </td>
                                <td>
                                    {{ $row->course_name??"" }}
                                </td>
                                <td>
                                    @lang($row->status_study_name ?? 'Chưa cập nhật')
                                </td>
                                <td class="text-center">
                                    {{ $row->total_attendance }}
                                </td>
                                <td class="text-center">
                                    {{ $row->total_schedules }}
                                </td>
                                <td class="text-danger text-center" style="font-weight: bold; font-size: 16px ">
                                    {{ $row->total_schedules - $row->total_attendance }}
                                </td>
                                
                                <td>
                                    <div class="input-group">
                                    <select class="form-control select2 ketoan_xacnhan" style="width: 100%;">
                                        @foreach ($ketoan as $key => $val)
                                            <option {{  $row->user->ketoan_xacnhan == $key  ? 'selected' : '' }} value="{{ $key }}">{{ __($val) }}</option>
                                        @endforeach
                                    </select>
                                    <span data-id="{{ $row->user->id }}" class="input-group-btn confirmClass">
                                        <a class="btn btn-primary">Lưu </a>
                                    </span></td>
                                    </div>
                                </td>
                            </tr>
                        </form>
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
