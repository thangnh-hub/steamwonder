{{-- 150 buổi từ ngày học chính thức --}}
<div class="box">
    <div class="box-header">
        <h3 class="box-title">@lang('List')</h3>
        <button data-toggle="modal" data-target="#import_excel" type="button" class="btn btn-warning btn-sm pull-right">
            <i class="fa fa-file-excel-o" ></i> @lang('Nhập bằng excel')</button>
            <form class="mr-1 pull-right"  action="{{ route('export.studentdept.version2') }}" method="get"
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
            <table class="table table-hover table-bordered">
                <thead>
                    <tr>
                        <th>@lang('Order')</th>
                        <th>@lang('Student code')</th>
                        <th>@lang('Full name')</th>
                        {{-- <th>@lang('CCCD')</th> --}}
                        <th>@lang('Gender')</th>
                        <th>@lang('Loại hợp đồng')</th>
                        <th>@lang('Area')</th>
                        <th>@lang('Class')</th>
                        <th>@lang('Course')</th>
                        <th>@lang('Ngày vào học chính thức')</th>
                        <th>@lang('Số ngày đã học')</th>
                        <th>@lang('Status Study')</th>
                        <th>@lang('Kế toán xác nhận')</th>
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
                                        href="{{ route('students.show', $row->id) }}">
                                        {{ $row->admin_code }}
                                    </a>
                                </td>
                                <td>
                                    {{ $row->name ?? '' }}
                                </td>
                                {{-- <td>
                                    {{ $row->json_params->cccd ?? '' }}
                                </td> --}}
                                <td>
                                    @lang($row->gender)
                                </td>
                                <td>
                                    {{ $row->json_params->contract_type??"" }}
                                </td>
                                <td>
                                    {{ $row->area_name }}
                                </td>
                                
                                <td>
                                    @if (isset($row->classs))
                                        <ul>
                                            @foreach ($row->classs as $i)
                                                <li>{{ $i->name }}</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </td>
                                <td>
                                    {{ $row->course->name??"" }}
                                </td>
                                <td>
                                    {{ $row->day_official!=""? date("d-m-Y",strtotime($row->day_official)):"Chưa cập nhật" }}
                                </td>
                                <td style="{{ $row->days_since_official>150?"background: #dd4b39 ;color:#fff":"" }}">
                                    {{ $row->days_since_official }} ngày
                                </td>

                                <td>
                                    @lang($row->status_study_name ?? 'Chưa cập nhật')
                                </td>
                                <td>
                                    <div class="input-group">
                                    <select class="form-control select2 ketoan_xacnhan" style="width: 100%;">
                                        @foreach ($ketoan as $key => $val)
                                            <option {{  $row->ketoan_xacnhan == $key  ? 'selected' : '' }} value="{{ $key }}">{{ __($val) }}</option>
                                        @endforeach
                                    </select>
                                    <span data-id="{{ $row->id }}" class="input-group-btn confirmClass">
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