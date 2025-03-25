@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection
@section('style')
    <style>
        .pd-0 {
            padding-left: 0px !important;
        }

        .modal-header {
            background: #3c8dbc;
            color: #fff;

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
            <div class="alert alert-danger alert-dismissible">
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
                            <span> {{ $this_class->students->count() }} </span>
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
                            <span>{{ $this_class->teacher_assistant }}</span>
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
                            <div>
                                <a class="btn btn-warning btn-sm " target="_blank"
                                    href="{{ route('evaluationclass.history', ['class_id' => $this_class->id ?? 0]) }}">
                                    @lang('Lịch sử nhận xét - đánh giá')
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            @endif
        </div>
        {{-- End search form --}}

        @if (isset($params['class_id']) && isset($params['from_date']) && isset($params['to_date']))
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                        @lang('Danh sách nhận xét đánh giá lớp')
                        {{ $this_class->name }}
                        {{ isset($params['from_date']) ? 'từ ngày: ' . $params['from_date'] : '' }}
                        {{ isset($params['to_date']) ? 'đến ngày: ' . $params['to_date'] : '' }}
                    </h3>
                    <form class=" pull-right" action="{{ route('export_evaluation') }}" method="get"
                        enctype="multipart/form-data">
                        <input type="hidden" name="from_date"
                            value="{{ isset($params['from_date']) ? $params['from_date'] : '' }}">
                        <input type="hidden" name="to_date"
                            value="{{ isset($params['to_date']) ? $params['to_date'] : '' }}">
                        <input type="hidden" name="class_id"
                            value="{{ isset($params['class_id']) ? $params['class_id'] : 0 }}">
                        <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-file-excel-o"></i>
                            @lang('Export nhận xét')</button>
                    </form>
                </div>
                <div class="box-body table-responsive">
                    @if (count($rows) == 0)
                        <div class="alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            @lang('not_found')
                        </div>
                    @else
                        <form action="{{ route('evaluations.save') }}" method="POST"
                            onsubmit="return confirm('@lang('confirm_action')')">
                            @csrf
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
                                        <th>@lang('Ngày đánh giá')</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($rows as $key => $row)
                                        <tr class="valign-middle">
                                            <input type="hidden" name="list[{{ $row->id }}][id]"
                                                value="{{ $row->id }}">
                                            <td>
                                                {{ $loop->index + 1 }}
                                            </td>

                                            <td>{{ $row->student->admin_code ?? '' }}</td>
                                            <td>
                                                <a target="_blank"
                                                    href="{{ route('students.show', $row->student->id) }}">{{ $row->student->name ?? '' }}
                                                    ({{ $row->student->admin_code ?? '' }})
                                                </a>
                                            </td>
                                            <td>
                                                <textarea onchange="updateAjax(this)" data-id="{{ $row->id }}" class="form-control ability"
                                                    name="list[{{ $row->id }}][json_params][ability]" cols="auto" rows="5">{{ $row->json_params->ability ?? '' }}</textarea>
                                            </td>
                                            <td>
                                                <textarea onchange="updateAjax(this)" data-id="{{ $row->id }}" class="form-control consciousness"
                                                    name="list[{{ $row->id }}][json_params][consciousness]" cols="auto" rows="5">{{ $row->json_params->consciousness ?? '' }}</textarea>
                                            </td>
                                            <td>
                                                <textarea onchange="updateAjax(this)" data-id="{{ $row->id }}" class="form-control knowledge"
                                                    name="list[{{ $row->id }}][json_params][knowledge]" cols="auto" rows="5">{{ $row->json_params->knowledge ?? '' }}</textarea>
                                            </td>
                                            <td>
                                                <textarea onchange="updateAjax(this)" data-id="{{ $row->id }}" class="form-control skill"
                                                    name="list[{{ $row->id }}][json_params][skill]" cols="auto" rows="5">{{ $row->json_params->skill ?? '' }}</textarea>
                                            </td>
                                            <td>
                                                {{ $row->updated_at }}
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <button type="submit" class="btn btn-info">
                                <i class="fa fa-save"></i> @lang('Save')
                            </button>

                        </form>
                    @endif
                </div>
            </div>
        @endif
    </section>

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

        document.addEventListener("copy", (event) => {
            event.preventDefault();
            alert("Copy bị vô hiệu hóa!");
        });

        document.addEventListener("cut", (event) => {
            event.preventDefault();
            alert("Cut bị vô hiệu hóa!");
        });

        document.addEventListener("paste", (event) => {
            event.preventDefault();
            alert("Paste bị vô hiệu hóa!");
        });

        document.addEventListener("contextmenu", (event) => {
            event.preventDefault();
            alert("Chuột phải bị khóa!");
        });

        document.addEventListener("keydown", (event) => {
            if (event.ctrlKey && (event.key === "u")) {
                event.preventDefault();
                alert("Thao tác bị chặn!");
            }
            if (event.key === "F12") {
                event.preventDefault();
                alert("Thao tác bị chặn!");
            }
        });
    </script>
@endsection
