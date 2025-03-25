@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection
@section('style')
    <style>
        .pd-0 {
            padding-left: 0px !important;
        }
    </style>
@endsection
@section('content-header')
    <!-- Content Header (Page header) -->
    {{-- <section class="content-header">
        <h1>
            @lang($module_name)
        </h1>
    </section> --}}
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
        {{-- Search form --}}
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">
                    @lang('THÔNG TIN CHI TIẾT NHẬN XÉT - ĐÁNH GIÁ')
                </h3>
            </div>
            @if (isset($this_class) && $this_class != null)
                @php
                    $quantity_student = \App\Models\UserClass::where('class_id', $this_class->id)
                        ->get()
                        ->count();
                    $teacher = \App\Models\Teacher::where('id', $this_class->json_params->teacher ?? 0)->first();
                    if ($this_class->assistant_teacher !== null && $this_class->assistant_teacher !== '') {
                        $assistantTeacherArray = json_decode($this_class->assistant_teacher, true);
                    }
                    $list = '';
                    foreach ($list_teacher as $key => $val) {
                        if (isset($assistantTeacherArray) && in_array($val->id, $assistantTeacherArray)) {
                            $list .= $val->name . '; ';
                        }
                    }
                @endphp
                <div class=" box-header">
                    <div class="col-md-4 pd-0">
                        <div class="form-group">
                            <label><strong>Lớp học: </strong></label>
                            <span>{{ $this_class->name }}</span>
                        </div>
                        <div class="form-group">
                            <label><strong>Giảng viên: </strong></label>
                            <span>{{ $teacher->name ?? '' }}</span>
                        </div>
                        <div class="form-group">
                            <label><strong>Sĩ số: </strong></label>
                            <span> {{ $quantity_student }} </span>
                        </div>
                        <div class="form-group">
                            <label><strong>Ca học: </strong></label>
                            <span>{{ $this_class->period->iorder }} ({{ $this_class->period->start_time ?? '' }} -
                                {{ $this_class->period->end_time ?? '' }})</span>
                        </div>
                    </div>
                    <div class="col-md-4 pd-0">
                        <div class="form-group">
                            <label><strong>Khóa học: </strong></label>
                            <span>{{ $this_class->course->name ?? '' }}</span>
                        </div>
                        <div class="form-group">
                            <label><strong>Trình độ: </strong></label>
                            <span>{{ $this_class->level->name ?? '' }}</span>
                        </div>
                        <div class="form-group">
                            <label><strong>Giảng viên phụ: </strong></label>
                            <span>{{ $list }}</span>
                        </div>
                        <div class="form-group">
                            <label><strong>Số buổi: </strong></label>
                            <span> {{ $this_class->total_attendance }}/{{ $this_class->total_schedules }} </span>
                        </div>
                    </div>
                    <div class="col-md-4 pd-0">
                        <div class="form-group">
                            <label><strong>Chương trình: </strong></label>
                            <span>{{ $this_class->syllabus->name ?? '' }}</span>
                        </div>

                        <div class="form-group">
                            <label><strong>Phòng học: </strong></label>
                            <span>{{ $this_class->room->name ?? '' }} (Khu vực:
                                {{ $this_class->area->name ?? '' }})</span>
                        </div>
                        <div class="form-group">
                            <label><strong>Bắt đầu | Kết thúc: </strong></label>
                            <span> {{ date('d-m-Y', strtotime($this_class->day_start)) }} |
                                {{ date('d-m-Y', strtotime($this_class->day_end)) }}</span>
                        </div>
                        <div class="form-group">
                            <button data-toggle="modal" data-target="#import_excel" type="button"
                                class="btn btn-success btn-sm import_evaluation">
                                <i class="fa fa-file-excel-o"></i> @lang('Nhập bằng excel')</button>

                            <button name="creat_submit" type="button" data-toggle="modal" data-target="#create_evaluation"
                                class="btn btn-primary btn-sm mr-10">@lang('Tạo nhận xét')</button>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        @if (isset($list_evolution_class))
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">@lang('Lịch sử nhận xét đánh giá lớp') {{ $this_class->name }}</h3>
                </div>
                <div class="box-body table-responsive">
                    @if (count($list_evolution_class) == 0)
                        <div class="alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            @lang('not_found')
                        </div>
                    @else
                        <form>
                            <table class="table table-hover table-bordered table-sm">
                                <thead>
                                    <tr>
                                        <th>@lang('STT')</th>
                                        <th>@lang('Từ ngày')</th>
                                        <th>@lang('Đến ngày')</th>
                                        <th>@lang('Lớp')</th>
                                        <th>@lang('Xem')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($list_evolution_class as $row)
                                        @if ($row->from_date != '' && $row->to_date != '')
                                            <tr class="valign-middle">
                                                <td>
                                                    {{ $loop->index + 1 }}
                                                </td>
                                                <td>
                                                    {{ $row->from_date != '' ? date('d-m-Y', strtotime($row->from_date)) : 'Chưa nhập ngày bắt đầu' }}
                                                </td>
                                                <td>
                                                    {{ $row->to_date != '' ? date('d-m-Y', strtotime($row->to_date)) : 'Chưa nhập ngày kết thúc' }}
                                                </td>
                                                <td>
                                                    {{ $this_class->name ?? '' }}
                                                </td>

                                                <td>
                                                    <a target="_blank"
                                                        href="{{ route('evaluations.index', ['class_id' => $this_class->id, 'from_date' => $row->from_date, 'to_date' => $row->to_date]) }}
                                                        ">@lang('Xem chi tiết')</a>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </form>
                    @endif
                </div>
            </div>
        @endif
    </section>
    <div id="import_excel" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">@lang('Import Excel')</h4>
                </div>
                <div class="modal-body">
                    <p class="text-danger">Để đảm bảo việc tính toán xử lý dữ liệu tự động và cập nhật mẫu Báo cáo đánh giá nhận xét mới. Chức năng import hiện tại sẽ tạm khóa. Vui lòng liên hệ bộ phận kỹ thuật nếu cần.</p>
                </div>
                {{-- Tạm dừng chức năng import --}}
                {{-- <form class="add-evolation-excel" action="{{ route('evaluations.store') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body row">
                        <input type="hidden" name="class_id"
                            value="{{ isset($params['class_id']) ? $params['class_id'] : 0 }}">
                        <input type="hidden" name="teacher_id" value="{{ isset($teacher->id) ? $teacher->id : 0 }}">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label><strong>@lang('From date'): <small class="text-red">*</small></strong></label>
                                <input required type="date" class="form-control start_date_excel" name="from_date"
                                    value="{{ isset($params['from_date']) ? $params['from_date'] : date('Y-m-d') }}">
                            </div>
                            <div class="form-group">
                                <label><strong>@lang('To date'): <small class="text-red">*</small> </strong></label>
                                <input required type="date" class="form-control end_date_excel" name="to_date"
                                    value="{{ isset($params['to_date']) ? $params['to_date'] : '' }}">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>@lang('Chọn tệp') <a href="{{ url('data/images/Import_excel.png') }}"
                                        target="_blank">(@lang('Minh họa file excel'))</a></label>
                                <small class="text-red">*</small>
                                <input id="file" class="form-control" type="file" required name="file"
                                    placeholder="@lang('Select File')" value="">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" style="text-align: center">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-file-excel-o"
                                aria-hidden="true"></i> @lang('Import')</button>
                    </div>
                </form> --}}
            </div>

        </div>
    </div>

    <div id="create_evaluation" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">@lang('Tạo nhận xét cho lớp') {{ $this_class->name }}</h4>
                </div>
                <form class="add-evolation-excel" action="{{ route('evaluations.create') }}" method="get"
                    enctype="multipart/form-data">
                    <div class="modal-body row">
                        <input type="hidden" name="class_id" value="{{ $this_class->id }}">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label><strong>@lang('From date'): <small class="text-red">*</small></strong></label>
                                <input required type="date" class="form-control start_date_excel" name="from_date"
                                    value="{{ isset($params['from_date']) ? $params['from_date'] : date('Y-m-d') }}">
                            </div>
                            <div class="form-group">
                                <label><strong>@lang('To date'): <small class="text-red">*</small> </strong></label>
                                <input required type="date" class="form-control end_date_excel" name="to_date"
                                    value="{{ isset($params['to_date']) ? $params['to_date'] : '' }}" max="{{ now()->toDateString() }}">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" style="text-align: center">
                        <button type="submit" class="btn btn-primary">@lang('Tạo nhận xét')</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection
@section('script')
    <script>
        function updateAjax(th) {
            let _id = $(th).attr('data-id');
            var _ability = $(th).parents('tr').find('.ability').val();
            var _consciousness = $(th).parents('tr').find('.consciousness').val();
            var _knowledge = $(th).parents('tr').find('.knowledge').val();
            var _skill = $(th).parents('tr').find('.skill').val();
            let url = "{{ route('ajax.update.evaluation') }}/";
            $.ajax({
                type: "GET",
                url: url,
                data: {
                    id: _id,
                    ability: _ability,
                    consciousness: _consciousness,
                    knowledge: _knowledge,
                    skill: _skill,
                },
                success: function(response) {

                },
                error: function(response) {
                    let errors = response.responseJSON.message;
                    alert(errors);
                }
            });
        }

        $(document).ready(function() {
            $(".add-evolation").submit(function(event) {
                var startDate = $(".start_date").val();
                var endDate = $(".end_date").val();

                if (startDate > endDate) {
                    alert("Ngày kết thúc phải lớn hơn ngày bắt đầu.");
                    event.preventDefault();
                    return;
                }
            });
            $(".add-evolation-excel").submit(function(event) {
                var startDate = $(".start_date_excel").val();
                var endDate = $(".end_date_excel").val();

                if (startDate > endDate) {
                    alert("Ngày kết thúc phải lớn hơn ngày bắt đầu.");
                    event.preventDefault();
                    return;
                }
            });
        });
    </script>
@endsection