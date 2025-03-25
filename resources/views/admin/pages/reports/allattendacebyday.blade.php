@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection
@section('style')
    <style>
        #alert-config{
            width: auto !important;
        }
        th {
            text-align: center;
            vertical-align: middle !important;
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
<div id="alert-config">

</div>
    <!-- Main content -->
    <section class="content">
        {{-- Search form --}}
        <div class="box box-default">

            <div class="box-header with-border">
                <h3 class="box-title">@lang('Filter')</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <form action="{{ route('report.all.attendance.byday') }}" method="GET">
                <div class="box-body">
                    <div class="row">

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Ngày') </label>
                                <input type="date" class="form-control" name="date" placeholder="@lang('Nhập tên lớp')"
                                    value="{{ isset($params['date']) ? $params['date'] : '' }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Học viên')</label>
                                <input type="text" name="keyword" class="form-control"
                                    value="{{ isset($params['keyword']) ? $params['keyword'] : '' }}"
                                    placeholder="Nhập tên học viên, mã học viên">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Lớp')</label>
                                <select name="class_id" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($class as $item)
                                        <option value="{{ $item->id }}"
                                            {{ isset($params['class_id']) && $params['class_id'] == $item->id ? 'selected' : '' }}>
                                            {{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                       
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Trạng thái điểm danh')</label>
                                <select name="status" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($status as $key => $item)
                                        @if($key!="attendant")
                                            <option value="{{ $key }}"{{ isset($params['status']) && $params['status'] == $key ? 'selected' : '' }}>{{ __($item) }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Khu vực')</label>
                                <select name="list_area_id[]" id="" class="form-control select2" style="width: 100%;" multiple>
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($area as $item)
                                        <option value="{{ $item->id }}"
                                            {{ isset($params['list_area_id']) && in_array( $item->id ,$params['list_area_id']) ? 'selected' : '' }}>
                                            {{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Filter')</label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-sm mr-10">@lang('Submit')</button>
                                    <a class="btn btn-default btn-sm" href="{{ route('report.all.attendance.byday') }}">
                                        @lang('Reset')
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </form>
        </div>
        {{-- End search form --}}

        <div class="box">
            <div class="box-header">
                <h3 class="box-title">@lang('Danh sách học viên vắng mặt - đi muộn '){{ isset($params['date'])? date('d-m-Y', strtotime($params['date'])) :"" }}</h3>
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
                                <th>STT</th>
                                <th>Mã học viên</th>
                                <th>Tên</th>
                                <th>Lớp</th>
                                <th>@lang('Khu vực')</th>
                                <th>Trạng thái</th>
                                <th>Ghi chú trạng thái</th>
                                <th>Ghi chú GV</th>
                                <th>Link điểm danh</th>
                                <th>Đã báo phụ huynh</th>
                                <th>Hình thức thông báo</th>
                                <th>Ghi chú (Phòng đào tạo)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $item)
                            <tr class="valign-middle">
                                <td>{{ $loop->index + 1 }}</td>
                                <td><a target="_blank" href="{{ route('students.show', $item->user_id) }}"><strong style="font-size: 14px;">{{ $item->student->admin_code??"" }}</strong></a></td>
                                <td><a target="_blank" href="{{ route('students.edit', $item->user_id) }}">{{ $item->student->name??"" }}</a></td>
                                <td><a target="_blank" href="{{ route('classs.show', $item->class_id) }}">{{ $item->class->name??"" }}</a></td>
                                <td>{{ $item->student->area->name ?? '' }}</td>
                                <td>{{ __($item->status) }}</td>
                                <td>{{ __($item->json_params->value ?? "") }} {{ $item->status=="late"?"phút":"" }}</td>
                                <td>{{ $item->note_teacher ?? '' }}</td>
                                <td><a target="_blank" href="{{ route('attendances.index', ['schedule_id' => $item->schedule_id]) }}">Link điểm danh</a></td>
                                <td><input type="checkbox" {{ (isset($item->json_params->is_contact_to_parents) && $item->json_params->is_contact_to_parents =="1")?"checked":""}} class="is_contact_to_parents" value="{{ $item->json_params->is_contact_to_parents??0 }}"  onchange="updateCheckboxValue(this) "></td>
                                <td>
                                    <select class="form-control parents_method" >
                                        @foreach (App\Consts::CONTACT_PARENTS_METHOD as $key => $method)
                                            <option value="{{ $key }}" {{ (isset($item->json_params->parents_method) && $item->json_params->parents_method == $key) ?"selected":""}}>
                                                {{ __($method) }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <input placeholder="Nhập ghi chú"   type="text" class="form-control note" value="{{ $item->note??"" }}" >
                                        <span data-id="{{ $item->id }}" onclick="updateAjax(this)" class="input-group-btn">
                                            <a class="btn btn-primary">Lưu </a>
                                        </span>
                                    </div>
                                </td>
                            </tr>
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
    </section>
    
@endsection

@section('script')
    <script>
        function updateCheckboxValue(checkbox) {
            if (checkbox.checked) {
                checkbox.value = 1;
            } else {
                checkbox.value = 0;
            }
        }
        function updateAjax(th){
            let _id = $(th).attr('data-id');
            var _note=$(th).parents('tr').find('.note').val();
            var _is_contact_to_parents=$(th).parents('tr').find('.is_contact_to_parents').val();
            var _parents_method=$(th).parents('tr').find('.parents_method').val();
            let url = "{{ route('ajax.update.note') }}/";
            $.ajax({
                type: "GET",
                url: url,
                data: {
                    id: _id,
                    note: _note,
                    is_contact_to_parents: _is_contact_to_parents,
                    parents_method: _parents_method,
                },
                success: function(response) {
                    $("#alert-config").append('<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Đã lưu cập nhật</div>');
                    setTimeout(function() {
                        $(".alert-success").fadeOut(2000, function() {});
                    }, 800);
                },
                error: function(response) {
                    let errors = response.responseJSON.message;
                    alert(errors);
                }
            });
        }
        
    </script>
@endsection
