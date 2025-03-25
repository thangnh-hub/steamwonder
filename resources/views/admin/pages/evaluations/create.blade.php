@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection
@section('style')
    <style>
        body,
        input,
        textarea {
            user-select: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
        }

        .pd-0 {
            padding-left: 0px !important;
        }

        .modal-header {
            background: #3c8dbc;
            color: #fff;

        }

        th {
            text-align: center;
            vertical-align: middle !important;
        }

        .table>thead>tr>th {
            font: bold 14px/28px "RobotoCondensed-Regular";
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
                <h3 class="box-title">@lang('THÔNG TIN CHI TIẾT NHẬN XÉT - ĐÁNH GIÁ')</h3>
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
                            <span>{{ $this_class->teacher->name ?? '' }}</span>
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
                                <a class="btn btn-warning btn-sm" target="_blank"
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
                <div class="box-body">
                    @if (count($rows) == 0)
                        <div class="alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            @lang('not_found')
                        </div>
                    @else
                        <form action="{{ route('evaluations.store') }}" method="POST"
                            onsubmit="return confirm('@lang('confirm_action')')">
                            @csrf
                            <table class="table table-hover table-bordered sticky">
                                <thead>
                                    <tr>
                                        <th rowspan="2">@lang('STT')</th>
                                        <th rowspan="2">@lang('Mã HV')</th>
                                        <th rowspan="2">@lang('Student')</th>
                                        <th rowspan="2" style="width: 90px">@lang('Học lực') <p>(Thang 10đ)</p>
                                        </th>
                                        <th colspan="3">@lang('Ý thức')</th>
                                        <th colspan="3">@lang('Kiến thức')</th>
                                        <th colspan="4">@lang('Kỹ năng')</th>
                                        <th rowspan="2">@lang('Ngày đánh giá')</th>
                                    </tr>
                                    <tr>
                                        <th style="width: 90px">
                                            @lang('Chuyên cần') <p>(Thang 10đ)</p>
                                        </th>
                                        <th style="width: 90px">
                                            @lang('Làm BT') <p>(Thang 10đ)</p>
                                        </th>
                                        <th style="width: 200px">
                                            @lang('Tương tác')
                                        </th>
                                        <th style="width: 100px">
                                            @lang('Phát âm') <p>(Thang 10đ)</p>
                                        </th>
                                        <th style="width: 100px">
                                            @lang('Từ vựng') <p>(Thang 10đ)</p>
                                        </th>
                                        <th style="width: 100px">
                                            @lang('Ngữ pháp') <p>(Thang 10đ)</p>
                                        </th>
                                        <th style="width: 100px">
                                            @lang('Nghe') <p>(Thang 10đ)</p>
                                        </th>
                                        <th style="width: 100px">
                                            @lang('Nói') <p>(Thang 10đ)</p>
                                        </th>
                                        <th style="width: 100px">
                                            @lang('Đọc') <p>(Thang 10đ)</p>
                                        </th>
                                        <th style="width: 100px">
                                            @lang('Viết') <p>(Thang 10đ)</p>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($rows as $key => $row)
                                        @php
                                            $item = $row->json_params->version_1 ?? null;
                                        @endphp
                                        <tr class="valign-middle">
                                            <input type="hidden" name="evaluations[{{ $row->id }}][id]"
                                                value="{{ $row->id }}">
                                            <td rowspan="2">
                                                {{ $loop->index + 1 }}
                                            </td>

                                            <td rowspan="2">{{ $row->student->admin_code ?? '' }}</td>
                                            <td rowspan="2">
                                                <a target="_blank" href="{{ route('students.show', $row->student->id) }}">
                                                    {{ $row->student->name ?? '' }}
                                                    ({{ $row->student->admin_code ?? '' }})
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            </td>
                                            <td rowspan="2" class="text-center">
                                                <select data-id="{{ $row->id }}"
                                                    name="evaluations[{{ $row->id }}][json_params][version_1][ability]"
                                                    class="select2 form-control ability_{{ $row->id }} evaluation_update">
                                                    <option value="Chưa đánh giá">Chưa đánh giá</option>
                                                    @for ($i = 10; $i > 0; $i--)
                                                        <option value="{{ $i }}"
                                                            {{ isset($item->ability) && $item->ability == $i ? 'selected' : '' }}>
                                                            {{ $i }}
                                                        </option>
                                                    @endfor
                                                </select>
                                            </td>
                                            <td class="text-center">
                                                <select data-id="{{ $row->id }}"
                                                    class="select2 form-control consciousness_chuyen_can_{{ $row->id }} evaluation_update"
                                                    name="evaluations[{{ $row->id }}][json_params][version_1][consciousness][chuyen_can]">
                                                    <option value="Chưa đánh giá">Chưa đánh giá</option>
                                                    @for ($i = 10; $i > 0; $i--)
                                                        <option value="{{ $i }}"
                                                            {{ isset($item->consciousness->chuyen_can) && $item->consciousness->chuyen_can == $i ? 'selected' : '' }}>
                                                            {{ $i }}
                                                        </option>
                                                    @endfor
                                                </select>
                                            </td>
                                            <td class="text-center">
                                                <select data-id="{{ $row->id }}"
                                                    class="select2 form-control consciousness_bai_tap_{{ $row->id }} evaluation_update"
                                                    name="evaluations[{{ $row->id }}][json_params][version_1][consciousness][bai_tap]">
                                                    <option value="Chưa đánh giá">Chưa đánh giá</option>
                                                    @for ($i = 10; $i > 0; $i--)
                                                        <option value="{{ $i }}"
                                                            {{ isset($item->consciousness->bai_tap) && $item->consciousness->bai_tap == $i ? 'selected' : '' }}>
                                                            {{ $i }}
                                                        </option>
                                                    @endfor
                                                </select>
                                            </td>
                                            <td>
                                                <select data-id="{{ $row->id }}"
                                                    class="select2 form-control consciousness_tuong_tac_{{ $row->id }} evaluation_update"
                                                    name="evaluations[{{ $row->id }}][json_params][version_1][consciousness][tuong_tac]">
                                                    <option value="Chưa đánh giá">Chưa đánh giá</option>
                                                    @foreach (\App\Consts::LIST_TUONG_TAC_NHAN_XET as $key => $val)
                                                        <option value="{{ $val }}"
                                                            {{ isset($item->consciousness->tuong_tac) && $item->consciousness->tuong_tac == $val ? 'selected' : '' }}>
                                                            {{ $val }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="text-center">
                                                <select data-id="{{ $row->id }}"
                                                    class="select2 form-control knowledge_phat_am_{{ $row->id }} evaluation_update"
                                                    name="evaluations[{{ $row->id }}][json_params][version_1][knowledge][phat_am]">
                                                    <option value="Chưa đánh giá">Chưa đánh giá</option>
                                                    @for ($i = 10; $i > 0; $i--)
                                                        <option value="{{ $i }}"
                                                            {{ isset($item->knowledge->phat_am) && $item->knowledge->phat_am == $i ? 'selected' : '' }}>
                                                            {{ $i }}
                                                        </option>
                                                    @endfor
                                                </select>
                                            </td>
                                            <td class="text-center">
                                                <select data-id="{{ $row->id }}"
                                                    class="select2 form-control knowledge_tu_vung_{{ $row->id }} evaluation_update"
                                                    name="evaluations[{{ $row->id }}][json_params][version_1][knowledge][tu_vung]">
                                                    <option value="Chưa đánh giá">Chưa đánh giá</option>
                                                    @for ($i = 10; $i > 0; $i--)
                                                        <option value="{{ $i }}"
                                                            {{ isset($item->knowledge->tu_vung) && $item->knowledge->tu_vung == $i ? 'selected' : '' }}>
                                                            {{ $i }}
                                                        </option>
                                                    @endfor
                                                </select>
                                            </td>
                                            <td class="text-center">
                                                <select data-id="{{ $row->id }}"
                                                    class="select2 form-control knowledge_ngu_phap_{{ $row->id }} evaluation_update"
                                                    name="evaluations[{{ $row->id }}][json_params][version_1][knowledge][ngu_phap]">
                                                    <option value="Chưa đánh giá">Chưa đánh giá</option>
                                                    @for ($i = 10; $i > 0; $i--)
                                                        <option value="{{ $i }}"
                                                            {{ isset($item->knowledge->ngu_phap) && $item->knowledge->ngu_phap == $i ? 'selected' : '' }}>
                                                            {{ $i }}
                                                        </option>
                                                    @endfor
                                                </select>
                                            </td>
                                            <td class="text-center">
                                                <select data-id="{{ $row->id }}"
                                                    class="select2 form-control skill_nghe_{{ $row->id }} evaluation_update"
                                                    name="evaluations[{{ $row->id }}][json_params][version_1][skill][nghe]">
                                                    <option value="Chưa đánh giá">Chưa đánh giá</option>
                                                    @for ($i = 10; $i > 0; $i--)
                                                        <option value="{{ $i }}"
                                                            {{ isset($item->skill->nghe) && $item->skill->nghe == $i ? 'selected' : '' }}>
                                                            {{ $i }}
                                                        </option>
                                                    @endfor
                                                </select>
                                            </td>
                                            <td class="text-center">
                                                <select data-id="{{ $row->id }}"
                                                    class="select2 form-control skill_noi_{{ $row->id }} evaluation_update"
                                                    name="evaluations[{{ $row->id }}][json_params][version_1][skill][noi]">
                                                    <option value="Chưa đánh giá">Chưa đánh giá</option>
                                                    @for ($i = 10; $i > 0; $i--)
                                                        <option value="{{ $i }}"
                                                            {{ isset($item->skill->noi) && $item->skill->noi == $i ? 'selected' : '' }}>
                                                            {{ $i }}
                                                        </option>
                                                    @endfor
                                                </select>
                                            </td>
                                            <td class="text-center">
                                                <select data-id="{{ $row->id }}"
                                                    class="select2 form-control skill_doc_{{ $row->id }} evaluation_update"
                                                    name="evaluations[{{ $row->id }}][json_params][version_1][skill][doc]">
                                                    <option value="Chưa đánh giá">Chưa đánh giá</option>
                                                    @for ($i = 10; $i > 0; $i--)
                                                        <option value="{{ $i }}"
                                                            {{ isset($item->skill->doc) && $item->skill->doc == $i ? 'selected' : '' }}>
                                                            {{ $i }}
                                                        </option>
                                                    @endfor
                                                </select>
                                            </td>
                                            <td class="text-center">
                                                <select data-id="{{ $row->id }}"
                                                    class="select2 form-control skill_viet_{{ $row->id }} evaluation_update"
                                                    name="evaluations[{{ $row->id }}][json_params][version_1][skill][viet]">
                                                    <option value="Chưa đánh giá">Chưa đánh giá</option>
                                                    @for ($i = 10; $i > 0; $i--)
                                                        <option value="{{ $i }}"
                                                            {{ isset($item->skill->viet) && $item->skill->viet == $i ? 'selected' : '' }}>
                                                            {{ $i }}
                                                        </option>
                                                    @endfor
                                                </select>
                                            </td>
                                            <td rowspan="2" class="text-center">
                                                {{ $row->updated_at }}
                                            </td>

                                        </tr>
                                        <tr>
                                            <td colspan="3">
                                                <textarea data-id="{{ $row->id }}"
                                                    class="form-control consciousness_ghi_chu_{{ $row->id }} evaluation_update" cols="auto" rows="5"
                                                    placeholder="Nhập nội dung ghi chú cho ý thức của học viên nếu có..."
                                                    name="evaluations[{{ $row->id }}][json_params][version_1][consciousness][ghi_chu]">{{ $item->consciousness->ghi_chu ?? '' }}</textarea>
                                            </td>
                                            <td colspan="3">
                                                <textarea data-id="{{ $row->id }}"
                                                    class="form-control knowledge_ghi_chu_{{ $row->id }} evaluation_update" cols="auto" rows="5"
                                                    placeholder="- Nếu học sinh có thang điểm kém/trung bình (ví dụ 6/10) → Ghi nhận xét gồm các nội dung sau:
+ Gọi tên cụ thể vấn đề
+ Giải pháp cho học sinh (Làm gì (bài nào)? Làm ntn (ghi âm, viết, ...)? Thời hạn (nộp hàng ngày, ...)"
                                                    name="evaluations[{{ $row->id }}][json_params][version_1][knowledge][ghi_chu]">{{ $item->knowledge->ghi_chu ?? '' }}</textarea>
                                            </td>
                                            <td colspan="4">
                                                <textarea data-id="{{ $row->id }}" class="form-control skill_ghi_chu_{{ $row->id }} evaluation_update"
                                                    cols="auto" rows="5"
                                                    placeholder="- Nếu học sinh có thang điểm kém/trung bình (ví dụ 6/10) → Ghi nhận xét gồm các nội dung sau:
+ Gọi tên cụ thể vấn đề
+ Giải pháp cho học sinh (Làm gì (bài nào)? Làm ntn (ghi âm, viết, ...)? Thời hạn (nộp hàng ngày, ...)"
                                                    name="evaluations[{{ $row->id }}][json_params][version_1][skill][ghi_chu]">{{ $item->skill->ghi_chu ?? '' }}</textarea>
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
        // Code function to update ajax lưu các phần nhận xét theo dòng
        $('.evaluation_update').change(function() {
            try {
                let _id = $(this).data('id');
                let _json_params = {};
                _json_params['consciousness'] = {};
                _json_params['knowledge'] = {};
                _json_params['skill'] = {};
                _json_params['ability'] = $('.ability_' + _id).val();
                _json_params['consciousness']['chuyen_can'] = $('.consciousness_chuyen_can_' + _id).val();
                _json_params['consciousness']['bai_tap'] = $('.consciousness_bai_tap_' + _id).val();
                _json_params['consciousness']['tuong_tac'] = $('.consciousness_tuong_tac_' + _id).val();
                _json_params['consciousness']['ghi_chu'] = $('.consciousness_ghi_chu_' + _id).val();
                _json_params['knowledge']['phat_am'] = $('.knowledge_phat_am_' + _id).val();
                _json_params['knowledge']['tu_vung'] = $('.knowledge_tu_vung_' + _id).val();
                _json_params['knowledge']['ngu_phap'] = $('.knowledge_ngu_phap_' + _id).val();
                _json_params['knowledge']['ghi_chu'] = $('.knowledge_ghi_chu_' + _id).val();
                _json_params['skill']['nghe'] = $('.skill_nghe_' + _id).val();
                _json_params['skill']['noi'] = $('.skill_noi_' + _id).val();
                _json_params['skill']['doc'] = $('.skill_doc_' + _id).val();
                _json_params['skill']['viet'] = $('.skill_viet_' + _id).val();
                _json_params['skill']['ghi_chu'] = $('.skill_ghi_chu_' + _id).val();

                let _data = {
                    id: _id,
                    json_params: JSON.stringify(_json_params)
                }
                let url = "{{ route('ajax.update.evaluation') }}/";
                $.ajax({
                    type: "GET",
                    url: url,
                    data: _data,
                    success: function(response) {
                        console.log(response);
                    },
                    error: function(response) {
                        let errors = response.responseJSON.message;
                        alert(errors);
                    }
                });
            } catch (error) {
                console.error('Lỗi xảy ra: ', error.message);
            }

        });
    </script>

    <script>
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
