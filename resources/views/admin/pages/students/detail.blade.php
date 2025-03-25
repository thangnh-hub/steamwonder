@extends('admin.layouts.app')
@push('style')
    <style>
        .invoice {
            margin: 10px 15px;
        }

        table {
            border: 1px solid #dddddd;
        }

        th {
            text-align: center;
            vertical-align: middle !important;
        }

        th,
        td {
            border: 1px solid #dddddd;
        }

        .modal-header {
            background: #3c8dbc;
            color: #fff;
            text-align: center;
        }

        .mb-2 {
            margin-bottom: 2rem;
        }

        .min-height {
            min-height: unset !important
        }

        @media print {

            #printButton,
            .hide-print {
                display: none;
                /* Ẩn nút khi in */
            }
        }
    </style>
@endpush
@section('title')
    @lang($module_name)
@endsection

@section('content')
    <!-- Main content -->
    <section class="content">
        {{-- <a  target="_blank" href="{{ route('print_student', ['student_id' => $detail->id]) }}"><button class="btn btn-primary mb-2">@lang('In báo cáo')</button></a> --}}
        <button id="printButton" onclick="window.print()" class="btn btn-primary mb-2">@lang('In thông tin')</button>
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

        <div class="box box-default">
            <div class="box-header with-border">
                <h2 class="box-title text-uppercase text-bold">
                    <i class="fa fa-user"></i> @lang('Thông tin học viên')
                </h2>
            </div>
            <div class="box-body">
                <div class="row invoice-info">
                    <div class="col-sm-4 invoice-col">
                        <address>
                            <p><strong>@lang('Họ và tên'): </strong>{{ $detail->name ?? 'Chưa cập nhật' }}</p>
                            {{-- <p><strong>@lang('Email'): </strong>{{ $detail->email ?? 'Chưa cập nhật' }}</p> --}}
                            <p><strong>@lang('Mã học viên'): </strong>{{ $detail->admin_code ?? 'Chưa cập nhật' }}</p>
                            {{-- <p><strong>@lang('Ngày sinh'):
                                </strong>{{ $detail->birthday ? date('d-m-Y', strtotime($detail->birthday)) : 'Chưa cập nhật' }}
                            </p> --}}
                        </address>
                    </div><!-- /.col -->
                    <div class="col-sm-4 invoice-col">
                        <address>
                            <p><strong>@lang('Ngày nhập học'):
                                </strong>{{ $detail->day_official != '' ? date('d-m-Y', strtotime($detail->day_official)) : 'Chưa cập nhật' }}
                            </p>
                            <p><strong>@lang('Loại hợp đồng'):
                                </strong>{{ $detail->json_params->contract_type ?? 'Chưa cập nhật' }}
                            </p>
                            {{-- <p><strong>@lang('SĐT'): </strong>{{ $detail->phone ?? 'Chưa cập nhật' }}</p> --}}
                            {{-- <p><strong>@lang('Số CMND/CCCD'): </strong>{{ $detail->json_params->cccd ?? 'Chưa cập nhật' }}</p> --}}
                            {{-- <p><strong>@lang('Ngày cấp'):
                                </strong>{{ $detail->json_params->date_range ?? 'Chưa cập nhật' }}</p> --}}
                            {{-- <p><strong>@lang('Cấp bởi'):
                                </strong>{{ $detail->json_params->issued_by ?? 'Chưa cập nhật' }}</p> --}}
                        </address>
                    </div><!-- /.col -->
                    <div class="col-sm-4 invoice-col">
                        <address>
                            {{-- <p><strong>@lang('Họ và tên bố'):
                                </strong>{{ $detail->json_params->dad_name ?? 'Chưa cập nhật' }}</p> --}}
                            {{-- <p><strong>@lang('Số điện thoại bố'):
                                </strong>{{ $detail->json_params->dad_phone ?? 'Chưa cập nhật' }}</p> --}}
                            {{-- <p><strong>@lang('Họ và tên mẹ'):
                                </strong>{{ $detail->json_params->mami_name ?? 'Chưa cập nhật' }}</p> --}}
                            {{-- <p><strong>@lang('Số điện thoại mẹ'):
                                </strong>{{ $detail->json_params->mami_phone ?? 'Chưa cập nhật' }}</p> --}}
                        </address>
                    </div><!-- /.col -->

                </div>
            </div>
        </div>

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title text-uppercase text-bold">
                    <i class="fa fa-graduation-cap"></i> @lang('Quá trình học tập')
                </h3>
                @if ($admin_auth->admin_type == 'staff')
                    <button type="button" class="btn btn-warning pull-right hide-print" data-toggle="modal"
                        data-target=".bd-example-modal-lg">
                        @lang('Thêm lịch sử lớp học')
                    </button>
                @endif
            </div>
            <div style="padding-top:0px" class="box-body">
                <div class="d-flex-wap table-responsive">
                    <table style="border: 1px solid #dddddd;" class="table table-hover table-striped ">
                        <thead>
                            <tr>
                                <th rowspan="2">@lang('Order')</th>
                                <th rowspan="2">@lang('Lớp')</th>
                                <th rowspan="2">@lang('Giáo viên')</th>
                                <th rowspan="2">@lang('Trình độ')</th>
                                {{-- <th rowspan="2">@lang('Chương trình')</th> --}}
                                <th rowspan="2">@lang('Ngày vào lớp')</th>
                                <th rowspan="2">@lang('Hình thức')</th>
                                <th colspan="2">@lang('Lộ trình học')</th>
                                <th rowspan="2">@lang('Ngày thi')</th>
                                <th colspan="7">@lang('Điểm ')</th>
                                <th colspan="3">@lang('Điểm danh')</th>
                                <th colspan="2">@lang('Bài tập về nhà')</th>

                                {{-- <th rowspan="2">@lang('Nhận xét đánh giá')</th> --}}
                            </tr>
                            <tr>
                                <th style="width: 70px">@lang('Tiêu chuẩn')</th>
                                <th style="width: 70px">@lang('GVVN/GVNN')</th>

                                <th style="width: 50px">@lang('Nghe')</th>
                                <th style="width: 50px">@lang('Nói')</th>
                                <th style="width: 50px">@lang('Đọc')</th>
                                <th style="width: 50px">@lang('Viết')</th>
                                <th style="width: 50px">@lang('TB')</th>
                                <th style="width: 100px">@lang('Xếp loại')</th>
                                <th style="width: 170px">@lang('Nhận xét')</th>
                                <th style="width: 70px">@lang('Có')</th>
                                <th style="width: 70px">@lang('Vắng')</th>
                                <th style="width: 70px">@lang('Muộn')</th>
                                {{-- <th style="width: 90px">@lang('Có làm')</th> --}}
                                <th style="width: 90px">@lang('Không làm')</th>
                                <th style="width: 90px">@lang('Làm thiếu')</th>


                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($list_class as $key => $item)
                                {{-- @dd($item) --}}
                                <tr class="valign-middle">
                                    <td>
                                        {{ $loop->index + 1 }}
                                    </td>
                                    <td>
                                        {{ $item->name ?? '' }}
                                    </td>
                                    <td>
                                        {{ $item->teacher ?? '' }}
                                    </td>

                                    <td>
                                        {{ $item->level->name ?? '' }}
                                    </td>

                                    {{-- <td>
                                        {{ $item->syllabus->name ?? '' }}
                                    </td> --}}


                                    <td>
                                        {{ isset($item->day_in_class) && $item->day_in_class != '' ? date('d-m-Y', strtotime($item->day_in_class)) : '' }}
                                    </td>
                                    <td>
                                        {{ $item->status != '' ? App\Consts::USER_CLASS_STATUS[$item->status] ?? $item->status : '' }}
                                    </td>
                                    <td>{{ $item->lesson_number ?? '' }}</td>
                                    <td>{{ $item->total_schedules_gv ?? '' }} / {{ $item->total_schedules_gvnn ?? '' }}
                                    </td>

                                    <td>
                                        {{ $item->day_exam ?? '' }}
                                    </td>
                                    <td>
                                        {{ $item->score_listen }}
                                    </td>
                                    <td>
                                        {{ $item->score_speak }}
                                    </td>
                                    <td>
                                        {{ $item->score_read }}
                                    </td>
                                    <td>
                                        {{ $item->score_write }}
                                    </td>
                                    <td>
                                        {{ $item->score_average }}
                                    </td>
                                    <td>
                                        {{ $item->status_rank != '' ? App\Consts::ranked_academic[$item->status_rank] ?? $item->status_rank : 'Chưa xác định' }}
                                    </td>
                                    <td>
                                        {{ $item->note_score }}
                                    </td>

                                    <td>{{ $item->attendant ?? '' }}</td>

                                    <td>
                                        {{ $item->absent }}
                                        @if ($item->absent > 0)
                                            (CP: {{ $item->absent_has_reason }}, KP: {{ $item->absent - $item->absent_has_reason }})
                                            [{{ $item->string_absent }}]
                                        @endif
                                    </td>
                                    <td>
                                        {{ $item->late }} lần
                                        @if ($item->late > 0)
                                            (Tổng: {{ $item->count_late }} phút)
                                        @endif
                                    </td>

                                    {{-- <td>
                                        @if ($item->is_homework_have > 0)
                                            {{ $item->is_homework_have }} lần
                                        @endif
                                    </td> --}}
                                    <td>
                                        @if ($item->is_homework_not_have > 0)
                                            {{ $item->is_homework_not_have }} lần
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->is_homework_did_not_complete > 0)
                                            {{ $item->is_homework_did_not_complete }} lần
                                        @endif
                                    </td>


                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @if (count($list_class) > 0)
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title text-uppercase text-bold">
                        <i class="fa fa-star-half-empty"></i> @lang('Đánh giá nhận xét')
                    </h3>
                    @if ($admin_auth->admin_type == 'staff')
                        <button type="button" class="btn btn-warning pull-right hide-print" data-toggle="modal"
                            data-target=".bd-example-modal-lg-evoluation">
                            @lang('Thêm đánh giá nhận xét cho lớp')
                        </button>
                    @endif
                </div>
                @if (count($list_evolution) > 0)
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th style="width:8%">@lang('Từ ngày')</th>
                                        <th style="width:8%">@lang('Đến ngày')</th>
                                        <th style="width:10%">@lang('Lớp')</th>
                                        <th style="width:10%">@lang('Học lực')</th>
                                        <th style="width:21%">@lang('Ý thức')</th>
                                        <th style="width:21%">@lang('Kiến thức')</th>
                                        <th style="width:21%">@lang('Kỹ năng')</th>
                                    </tr>
                                </thead>
                                <tbody class="">
                                    @foreach ($list_evolution as $value)
                                        @if (
                                            $value->from_date &&
                                                $value->to_date &&
                                                ((isset($value->json_params->ability) && $value->json_params->ability != '') ||
                                                    (isset($value->json_params->consciousness) && $value->json_params->consciousness != '') ||
                                                    (isset($value->json_params->knowledge) && $value->json_params->knowledge != '') ||
                                                    (isset($value->json_params->skill) && $value->json_params->skill != '')))
                                            <tr>
                                                <td>
                                                    {{ $value->from_date ? date('d-m-Y', strtotime($value->from_date)) : 'Chưa cập nhật' }}
                                                </td>
                                                <td>
                                                    {{ $value->to_date ? date('d-m-Y', strtotime($value->to_date)) : 'Chưa cập nhật' }}
                                                </td>
                                                <td>
                                                    {{ $value->class->name ?? '' }}
                                                </td>
                                                <td>
                                                    {!! isset($value->json_params->ability) ? nl2br($value->json_params->ability) : '' !!}
                                                </td>
                                                <td>
                                                    {!! isset($value->json_params->consciousness) ? nl2br($value->json_params->consciousness) : '' !!}
                                                </td>
                                                <td>
                                                    {!! isset($value->json_params->knowledge) ? nl2br($value->json_params->knowledge) : '' !!}
                                                </td>
                                                <td>
                                                    {!! isset($value->json_params->skill) ? nl2br($value->json_params->skill) : '' !!}
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        @if (isset($history_student) && count($history_student) > 0)
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title text-uppercase text-bold">
                        <i class="fa fa-history"></i> @lang('Lịch sử biến động')
                    </h3>
                </div>

                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>@lang('STT')</th>
                                    <th>@lang('Loại')</th>
                                    <th>@lang('Trạng thái cũ')</th>
                                    <th>@lang('Trạng thái mới')</th>

                                    <th>@lang('Lớp cũ')</th>
                                    <th>@lang('Lớp mới')</th>
                                    <th>@lang('Trạng thái đổi lớp')</th>
                                    <th>@lang('Ngày vào lớp')</th>

                                    <th>@lang('Ngày cập nhật')</th>
                                    <th>@lang('Người cập nhật')</th>
                                    <th>@lang('Ghi chú')</th>
                                    <th class="hide-print">@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody class="">

                                @foreach ($history_student as $val)
                                    <tr class="text-center">
                                        <td>
                                            {{ $loop->index + 1 }}
                                        </td>
                                        <td>
                                            @lang($val->type)
                                        </td>
                                        <td>
                                            {{ $val->status_old->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $val->status_new->name ?? '' }}
                                        </td>

                                        <td>
                                            {{ $val->class_old->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $val->class_new->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ isset($val->status_change_class) && $val->status_change_class != '' ? $user_class_status[$val->status_change_class] : '' }}
                                        </td>
                                        <td>
                                            {{ isset($val->json_params->day_in_class) && $val->json_params->day_in_class != '' ? date('d-m-Y', strtotime($val->json_params->day_in_class)) : '' }}
                                        </td>
                                        <td>
                                            {{ date('d-m-Y', strtotime($val->updated_at)) }}
                                        </td>
                                        <td>
                                            {{ $val->admin_updated->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $val->json_params->note_status_study ?? '' }}
                                        </td>
                                        <td class="hide-print">
                                            <button class="btn btn-sm btn-warning edit_history"
                                                data-id="{{ $val->id }}" data-toggle="tooltip"
                                                title="@lang('Update')" data-original-title="@lang('Update')">
                                                <i class="fa fa-pencil-square-o"></i>
                                            </button>
                                            <a class="btn btn-sm btn-danger"
                                                onclick="return confirm('@lang('confirm_action')')"
                                                href="{{ route('student.delete_history', $val->id) }}"
                                                data-toggle="tooltip" title="@lang('Delete')"
                                                data-original-title="@lang('Delete')">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        @if (isset($decisions) && count($decisions) > 0)
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title text-uppercase text-bold">
                        <i class="fa fa-history"></i> @lang('Đơn biến động')
                    </h3>
                </div>

                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>@lang('STT')</th>
                                    <th>@lang('Đơn biến động')</th>
                                    <th>@lang('Nội dung')</th>
                                    <th>@lang('Ngày biến động')</th>
                                    <th>@lang('Note')</th>
                                </tr>
                            </thead>
                            <tbody class="">

                                @foreach ($decisions as $decision)
                                    <tr class="valign-middle">
                                        <td>
                                            {{ $loop->index + 1 }}
                                        </td>
                                        <td>
                                            {{ __($decision->is_type) }}
                                        </td>

                                        <td>
                                            {{ $decision->code }}
                                        </td>
                                        <td>
                                            {{ date('d/m/Y', strtotime($decision->active_date)) }}
                                        </td>
                                        <td>
                                            {{ $decision->note }}
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

    </section>

    <div class="modal fade bd-example-modal-lg" data-backdrop="static" role="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-full">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text_change" id="myLargeModalLabel">Thêm lịch sử lớp học</h4>
                </div>
                <form action="{{ route('additional_class') }}" method="POST"
                    onsubmit="return confirm('@lang('Duyên Đỗ lưu ý đến bạn: Bạn có chắc chắn lưu thêm thông tin các lớp này?')')">
                    @csrf
                    <div class="modal-body ">
                        <div class="box-default">
                            <div class="box-body">
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped">
                                        <thead>
                                            <tr>
                                                <th style="width: 270px" rowspan="2">@lang('Lớp')</th>
                                                <th rowspan="2">@lang('Nhập ngày vào lớp')</th>
                                                <th rowspan="2">@lang('Hình thức')</th>
                                                <th colspan="5">@lang('Điểm')</th>
                                                <th rowspan="2">@lang('Chức năng')</th>
                                            </tr>
                                            <tr>
                                                <th>@lang('Nghe')</th>
                                                <th>@lang('Nói')</th>
                                                <th>@lang('Đọc')</th>
                                                <th>@lang('Viết')</th>
                                                <th>@lang('Nhận xét')</th>
                                            </tr>
                                        </thead>
                                        <tbody class="box_available box_available_history">
                                            <tr>
                                                <td>
                                                    <input type="hidden" name="user_id" value="{{ $detail->id }}">
                                                    <select required name="list_class[0][class_id]" style="width:100%"
                                                        class="form-control select2 select_class">
                                                        @foreach ($all_class as $class_item)
                                                            <option value="{{ $class_item->id ?? '' }}">
                                                                {{ $class_item->name ?? '' }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input class="form-control" type="date"
                                                        name="list_class[0][day_in_class]" value="">
                                                </td>

                                                <td>
                                                    <select style="width:100%" class="form-control select2"
                                                        name="list_class[0][user_class_status]" id="">
                                                        @foreach (App\Consts::USER_CLASS_STATUS as $k => $us_status)
                                                            <option value="{{ $k }}">
                                                                {{ $us_status }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input class="form-control" type="number"
                                                        name="list_class[0][score_listen]" value="0">
                                                </td>
                                                <td>
                                                    <input class="form-control" type="number"
                                                        name="list_class[0][score_speak]" value="0">
                                                </td>
                                                <td>
                                                    <input class="form-control" type="number"
                                                        name="list_class[0][score_read]" value="0">
                                                </td>
                                                <td>
                                                    <input class="form-control" type="number"
                                                        name="list_class[0][score_write]" value="0">
                                                </td>
                                                <td>
                                                    <textarea rows="1" class="form-control note" name="list_class[0][note]"></textarea>
                                                </td>

                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <button type="button" class="btn btn-primary add_class_history">
                                    Thêm lớp
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            Đóng
                        </button>
                        <button class="btn btn-primary" type="submit">
                            Lưu thay đổi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade bd-example-modal-lg-evoluation" data-backdrop="static" role="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-full">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text_change" id="myLargeModalLabel">Thêm lịch sử nhận xét đánh giá</h4>
                </div>
                <form action="{{ route('additional_evaluation') }}" method="POST"
                    onsubmit="return confirm('@lang('Duyên Đỗ lưu ý đến bạn: Bạn có chắc chắn lưu thêm thông tin các lớp này?')')">
                    @csrf
                    <div class="modal-body ">
                        <div class="box-default">
                            <div class="box-body">
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped">
                                        <thead>
                                            <tr>
                                                <th>@lang('Lớp')</th>
                                                <th>@lang('Từ ngày')</th>
                                                <th>@lang('Đến ngày')</th>
                                                <th>@lang('Học lực')</th>
                                                <th>@lang('Ý thức')</th>
                                                <th>@lang('Kiến thức')</th>
                                                <th>@lang('Kỹ năng')</th>
                                                <th>@lang('Chức năng')</th>
                                            </tr>
                                        </thead>
                                        <tbody class="box_available box_available_history_evaluation">
                                            <tr>
                                                <td>
                                                    <input type="hidden" name="user_id" value="{{ $detail->id }}">
                                                    <select required name="list[0][class_id]" style="width:100%"
                                                        class="form-control select2 select_class">
                                                        @foreach ($list_class as $class_item)
                                                            <option value="{{ $class_item->id ?? '' }}">
                                                                {{ $class_item->name ?? '' }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input class="form-control" type="date" name="list[0][from_date]"
                                                        value="">
                                                </td>
                                                <td>
                                                    <input class="form-control" type="date" name="list[0][to_date]"
                                                        value="">
                                                </td>
                                                <td>
                                                    <textarea rows="1" class="form-control" name="list[0][ability]"></textarea>
                                                </td>
                                                <td>
                                                    <textarea rows="1" class="form-control" name="list[0][consciousness]"></textarea>
                                                </td>
                                                <td>
                                                    <textarea rows="1" class="form-control" name="list[0][knowledge]"></textarea>
                                                </td>
                                                <td>
                                                    <textarea rows="1" class="form-control" name="list[0][skill]"></textarea>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <button type="button" class="btn btn-primary add_evaluation_history">
                                    Thêm lớp
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            Đóng
                        </button>
                        <button class="btn btn-primary" type="submit">
                            Lưu thay đổi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade modal_history" data-backdrop="static" role="dialog" aria-hidden="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">@lang('Sửa lịch sử biến động')</h4>
                </div>
                <form action="{{ route('student.update_history_statusstudy') }}" method="POST"
                    onsubmit="return confirm('@lang('Duyên Đỗ lưu ý đến bạn: Bạn có chắc chắn cập nhật thông tin lịch sử ?')')">
                    @csrf
                    <div class="modal-body ">
                        <div class="box-default">
                            <div class="box-body">
                                <div class="table-responsive table_history">
                                    <table class="table table-hover table-striped">
                                        <thead>
                                            <tr>
                                                <th>@lang('Lớp')</th>
                                                <th>@lang('Từ ngày')</th>
                                                <th>@lang('Đến ngày')</th>
                                                <th>@lang('Học lực')</th>
                                                <th>@lang('Ý thức')</th>
                                                <th>@lang('Kiến thức')</th>
                                                <th>@lang('Kỹ năng')</th>
                                                <th>@lang('Chức năng')</th>
                                            </tr>
                                        </thead>
                                        <tbody class="box_history">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            Đóng
                        </button>
                        <button class="btn btn-primary" type="submit">
                            Lưu thay đổi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script>
        function del_class_history(th) {
            $(th).parents('tr').fadeOut(500, function() {
                $(th).parents('tr').remove();
            });
        }

        $('.add_class_history').click(function() {
            var currentTime = $.now();
            var _html = `<tr>
                    <td>
                        <select required name="list_class[` + currentTime + `][class_id]" style="width: 100%" class="form-control select2 select_class">
                            @foreach ($all_class as $class_item)
                                <option value="{{ $class_item->id ?? '' }}">
                                    {{ $class_item->name ?? '' }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input name="list_class[` + currentTime + `][day_in_class]" class="form-control" type="date" value="">
                    </td>
                    <td>
                        <select style="width:100%" class="form-control select2"
                            name="list_class[` + currentTime + `][user_class_status]">
                            @foreach (App\Consts::USER_CLASS_STATUS as $k => $us_status)
                                <option

                                    value="{{ $k }}">
                                    {{ $us_status }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input class="form-control" type="number" name="list_class[` + currentTime + `][score_listen]" value="0">
                    </td>
                    <td>
                        <input class="form-control" type="number" name="list_class[` + currentTime + `][score_speak]" value="0">
                    </td>
                    <td>
                        <input class="form-control" type="number" name="list_class[` + currentTime + `][score_read]" value="0">
                    </td>
                    <td>
                        <input class="form-control" type="number" name="list_class[` + currentTime + `][score_write]" value="0">
                    </td>
                    <td>
                        <textarea  rows="1" class="form-control note" name="list_class[` + currentTime + `][note]"></textarea>
                    </td>

                    <td>
                        <button type="button" onclick="del_class_history(this)" class="btn btn-danger">
                            Xóa
                        </button>
                    </td>
                </tr>`;
            $('.box_available_history').append(_html);
            $('.select2').select2();
        })

        $('.add_evaluation_history').click(function() {
            var currentTime = $.now();
            var _html = `<tr>
                        <td>
                            <input type="hidden" name="user_id" value="{{ $detail->id }}">
                            <select required name="list[` + currentTime + `][class_id]" style="width:100%" class="form-control select2 select_class">
                                @foreach ($list_class as $class_item)
                                    <option value="{{ $class_item->id ?? '' }}">
                                        {{ $class_item->name ?? '' }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input class="form-control" type="date" name="list[` + currentTime + `][from_date]" value="">
                        </td>
                        <td>
                            <input class="form-control" type="date" name="list[` + currentTime + `][to_date]" value="">
                        </td>
                        <td>
                            <textarea rows="1" class="form-control" name="list[` + currentTime + `][ability]"></textarea>
                        </td>
                        <td>
                            <textarea rows="1" class="form-control" name="list[` + currentTime + `][consciousness]"></textarea>
                        </td>
                        <td>
                            <textarea rows="1" class="form-control" name="list[` + currentTime + `][knowledge]"></textarea>
                        </td>
                        <td>
                            <textarea rows="1" class="form-control" name="list[` + currentTime + `][skill]"></textarea>
                        </td>
                        <td>
                        <button type="button" onclick="del_class_history(this)" class="btn btn-danger">
                            Xóa
                        </button>
                    </td>
                </tr>`;
            $('.box_available_history_evaluation').append(_html);
            $('.select2').select2();
        })

        $('.edit_history').click(function() {
            var _id = $(this).data('id');
            let _url = "{{ route('student.get_table_history') }}";
            var _html = $('.table_history');
            $.ajax({
                type: "GET",
                url: _url,
                data: {
                    "id": _id,
                },
                success: function(response) {
                    _view = response.data.html;
                    _html.html(_view);
                    $('.modal_history').modal('show');
                },
                error: function(response) {
                    var errors = response.responseJSON.errors;
                    var elementErrors = '';
                    $.each(errors, function(index, item) {
                        if (item === 'CSRF token mismatch.') {
                            item = translations.csrf_mismatch;
                        }
                        elementErrors += '<p>' + item + '</p>';
                    });
                    $('.box_alert').html(
                        elementErrors);
                }
            });
        })
    </script>
@endsection
