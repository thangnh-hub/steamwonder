@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection
@section('style')
    <style>
        .background-warning-yellow {
            background: #f9e7a2;
        }

        .font-weight-bold {
            font-weight: bold;
            font-size: 16px
        }

        th {
            text-align: center;
            vertical-align: middle !important;
        }
        #alert-config {
            width: auto !important;
        }
        @media print {
            #printButton, .hide-print{
                display: none;
                /* Ẩn nút khi in */
            }
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
        {{-- Search form --}}
        {{-- End search form --}}

        <div class="box">
            <div class="box-header">
                <h3 class="box-title">@lang($module_name)</h3>
                <button id="printButton" onclick="window.print()" class="btn btn-primary mb-2 pull-right">@lang('In thông tin')</button>
            </div>
            <div class="box-body table-responsive">
                <div id="alert-config">
                </div>
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
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>@lang('STT')</th>
                                <th>@lang('Ngày học')</th>
                                <th>@lang('Ca học')</th>
                                <th>@lang('Điểm danh lúc')</th>
                                <th>@lang('Lớp')</th>
                                <th>@lang('Trình độ')</th>
                                <th>@lang('Trạng thái')</th>
                                <th>@lang('Ghi chú chấm công giáo viên')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $item)
                                <tr> 
                                    <th>
                                        {{ $loop->index + 1 }}
                                    </th>
                                    <th>
                                        {{ date("d-m-Y",strtotime($item->date)) }}
                                    </th>
                                    <th>
                                    Ca {{ $item->period->iorder ?? '' }} ({{ $item->period->start_time ?? '' }} -
                                        {{ $item->period->end_time ?? '' }})
                                    </th>
                                      
                                    <th>
                                        {{ $item->attendance_time!=""?date("H:i:s d-m-Y",strtotime($item->attendance_time)):"" }}
                                    </th>
                                    <th>
                                        {{ $item->class->name??"" }}
                                    </th>
                                    <th>
                                        {{ $item->class->level->name??"" }}
                                    </th>
                                    <th>
                                        <p class="{{ $item->status=="chuahoc" ?'text-red':"" }}" >{{ __($item->status??"") }}</p> 
                                     </th>
                                    
                                    <th>
                                        <div class="input-group">
                                            <input  type="text" class="form-control note" value="{{ $item->json_params->note_keeping_teacher??"" }}" >
                                            <span data-id="{{ $item->id }}" onclick="updatetkeepAjax(this)" class="input-group-btn hide-print">
                                                <a class="btn btn-primary">Lưu </a>
                                            </span></td>
                                        </div>
                                    </th>
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
<script>
    function updatetkeepAjax(th) {
            let _id = $(th).attr('data-id');
            var _note = $(th).parents('tr').find('.note').val();
            let url = "{{ route('ajax.update.note_keepingteacher') }}/";
            $.ajax({
                type: "GET",
                url: url,
                data: {
                    id: _id,
                    note: _note,
                },
                success: function(response) {
                    $("#alert-config").append(
                        '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Đã lưu cập nhật</div>'
                    );
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
