@extends('admin.layouts.app')

@section('title')
    {{ $module_name }}
@endsection

@section('style')
    <style>
        ul li {
            list-style: none;
        }
    </style>
@endsection

@section('content-header')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            {{ $module_name }}
        </h1>
    </section>
@endsection

@section('content')
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
            <form action="{{ route('gift_distribute_entry') }}" method="GET">
                <div class="box-body">
                    <div class="row">
                        {{-- <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Keyword') </label>
                                <input type="text" class="form-control" name="keyword" placeholder="@lang('keyword_note')"
                                    value="{{ isset($params['keyword']) ? $params['keyword'] : '' }}">
                            </div>
                        </div> --}}
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>@lang('Khóa học')</label>
                                <select class="form-control select2" name="course_id" id="">
                                    <option value="">Chọn</option>
                                    @foreach ($courses as  $val)
                                        <option value="{{ $val->id }}" {{ isset($params['course_id']) && $params['course_id'] == $val->id ? 'selected' : '' }}>
                                            {{ $val->name??"" }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Filter')</label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-sm mr-10">@lang('Submit')</button>
                                    <a class="btn btn-default btn-sm" href="{{ route('gift_distribute_entry') }}">
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
                <form action="{{ route('store_entry') }}" method="POST">
                    @csrf
                    <div  class="box-header with-border">
                        <h3 class="box-title">@lang('Xuất kho quà tặng học viên')</h3>
                    </div>
                    <div class="row" style="margin:0;padding-top:10px">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Cơ sở')<small class="text-red">*</small></label>
                                <select required class="area_id form-control" name="area_id" autocomplete="off">
                                    <option value="">Chọn</option>
                                    @foreach ($list_area as $key => $val)
                                        <option value="{{ $val->id }}"
                                            {{ $area_selected > 0 && $area_selected == $val->id ? 'selected' : '' }}>
                                            {{ $val->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Kho xuất')<small class="text-red">*</small></label>
                                <select required name="warehouse_id_deliver" class="warehouse_avaible form-control"
                                    autocomplete="off">
                                    @foreach ($list_warehouse as $key => $val)
                                        <option value="{{ $val->id }}"
                                            {{ isset($order_selected) && $order_selected->warehouse_id == $val->id ? 'selected' : '' }}>
                                            {{ $val->name }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>@lang('Tên phiếu xuất kho') <small class="text-red">*</small></label>
                                <input type="text" class="form-control"
                                    name="name" placeholder="@lang('Tên phiếu xuất kho')" value="Xuất kho quà tặng học viên"
                                    required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Ngày xuất') <small class="text-red">*</small></label>
                                <input required type="date" class="form-control" name="day_deliver"
                                    value="{{ old('day_deliver') ?? date('Y-m-d', time()) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Người tạo phiếu')</label>
                                <input type="text" class="form-control" 
                                    value="{{ $admin_auth->name . ' (' . $admin_auth->admin_code . ')' }}" disabled>
                            </div>
                        </div>
                    </div>

                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>@lang('STT')</th>
                                <th>@lang('Mã HV')</th>
                                <th>@lang('Họ tên')</th>
                                <th>@lang('Khóa học')</th>
                                <th style="width:40%">@lang('DS Quà tặng')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($students as $val)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $val->admin_code ?? "" }}</td>
                                    <td>{{ $val->name ?? '' }}</td>
                                    <td class="course_name">{{ $val->course->name ?? '' }}</td>
                                    <td>
                                        <div style="display: flex; gap: 10px;">
                                            <ul>
                                                @foreach ($val->issued_gifts as $gift)
                                                    <li>
                                                        <input 
                                                            id="check_{{ $gift->product_id }}_{{ $val->id }}" 
                                                            type="checkbox" 
                                                            name="gifts[{{ $val->id }}][]" 
                                                            value="{{ $gift->id }}"
                                                            checked>
                                                        <label for="check_{{ $gift->product_id }}_{{ $val->id }}">
                                                            {{ $gift->product->name ?? 'Không xác định' }}
                                                        </label>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    @if($students->count() > 0)
                        <button type="submit" class="btn btn-success pull-right">
                            <i class="fa fa-plus"></i>  Tạo phiếu xuất kho 
                        </button>
                    @endif
                </form>
            </div>
        </div>
    </section>
    
@endsection
@section('script')
    <script>
        $('.area_id').change(function() {
            $('#post_related').html('');
            $('.tbody-order-asset').html('');
            var _id = $(this).val();
            let url = "{{ route('warehouse_by_area') }}";
            let _targetHTML = $('.warehouse_avaible');

            $.ajax({
                type: "POST",
                url: url,
                data: {
                    "_token": "{{ csrf_token() }}",
                    id: _id,
                },
                success: function(response) {
                    if (response.message == 'success') {
                        let list = response.data;
                        let _item = '';
                        if (list.length > 0) {
                            list.forEach(item => {
                                _item += '<option value="' + item.id + '">' + item
                                    .name + '</option>';
                            });
                            _targetHTML.html(_item);
                        }
                    } else {
                        _targetHTML.html('<option value="">@lang('Please select')</option>');
                    }
                },
                error: function(response) {
                    // Get errors
                    // let errors = response.responseJSON.message;
                    // _targetHTML.html('<tr><td colspan="5">' + errors + '</td></tr>');
                }
            });
        })
    </script>
@endsection
