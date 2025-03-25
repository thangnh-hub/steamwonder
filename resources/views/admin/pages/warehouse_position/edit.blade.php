@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang($module_name)
            <a class="btn btn-sm btn-warning pull-right" href="{{ route(Request::segment(2) . '.create') }}"><i
                    class="fa fa-plus"></i> @lang('Add')</a>

        </h1>
    </section>

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

        <form role="form" action="{{ route(Request::segment(2) . '.update', $detail->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-lg-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Create form')</h3>
                        </div>
                        @csrf
                        <div class="box-body">
                            <!-- Custom Tabs -->
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a href="#tab_1" data-toggle="tab">
                                            <h5>Thông tin chính <span class="text-danger">*</span></h5>
                                        </a>
                                    </li>
                                    <button type="submit" class="btn btn-info btn-sm pull-right">
                                        <i class="fa fa-save"></i> @lang('Save')
                                    </button>
                                </ul>

                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_1">
                                        <div class="d-flex-wap">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Kho') <small class="text-red">*</small></label>
                                                    <select name="warehouse_id" class="warehouse_id form-control select2"
                                                        required>
                                                        <option value="">Chọn</option>
                                                        @foreach ($list_warehouse as $key => $val)
                                                            <option value="{{ $val->id }}"
                                                                {{ isset($detail->warehouse_id) && $detail->warehouse_id == $val->id ? 'selected' : '' }}>
                                                                @lang($val->name ?? '')</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Parent element')</label>
                                                    <select name="parent_id" class="parent_id form-control select2">
                                                        <option value="">== @lang('ROOT') ==</option>
                                                        @if ($positions)
                                                            @foreach ($positions as $val)
                                                                @php
                                                                    if (isset($detail->id) && $detail->id == $val->id) {
                                                                        continue;
                                                                    }
                                                                    if(isset($detail->warehouse_id) && $detail->warehouse_id != $val->warehouse_id){continue;}
                                                                @endphp
                                                                @if (empty($val->parent_id))
                                                                    <option value="{{ $val->id }}"
                                                                        {{ isset($detail->parent_id) && $val->id != $detail->id && $detail->parent_id == $val->id ? 'selected' : '' }}>
                                                                        @lang($val->name)</option>
                                                                    {{-- Cấp 2 --}}
                                                                    @foreach ($positions as $val1)
                                                                        @php
                                                                            if (
                                                                                isset($detail->id) &&
                                                                                $detail->id == $val1->id
                                                                            ) {
                                                                                continue;
                                                                            }
                                                                        @endphp
                                                                        @if ($val1->parent_id == $val->id)
                                                                            <option value="{{ $val1->id }}"
                                                                                {{ isset($detail->parent_id) && $val1->id != $detail->id && $detail->parent_id == $val1->id ? 'selected' : '' }}>
                                                                                - - @lang($val1->name)</option>
                                                                            {{-- Cấp 3 --}}
                                                                            {{-- @foreach ($positions as $val2)
                                                                                @php
                                                                                    if (
                                                                                        isset($detail->id) &&
                                                                                        $detail->id == $val2->id
                                                                                    ) {
                                                                                        continue;
                                                                                    }
                                                                                @endphp
                                                                                @if ($val2->parent_id == $val1->id)
                                                                                    <option value="{{ $val2->id }}"
                                                                                        {{ isset($detail->parent_id) && $val2->id != $detail->id && $detail->parent_id == $val2->id ? 'selected' : '' }}>
                                                                                        - - - - @lang($val2->name)</option>
                                                                                @endif
                                                                            @endforeach --}}
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                            @endforeach
                                                        @endif

                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Tên vị trí') <small class="text-red">*</small></label>
                                                    <input type="text" class="form-control" name="name"
                                                        placeholder="@lang('Tên vị trí')" value="{{ $detail->name ?? '' }}"
                                                        required>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Trạng thái')</label>
                                                    <select name="status" class=" form-control select2">
                                                        @foreach ($status as $key => $val)
                                                            <option value="{{ $key }}"
                                                                {{ isset($detail->status) && $detail->status == $val ? 'selected' : '' }}>
                                                                @lang($val)</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>@lang('Mô tả') </label>
                                                    <textarea class="form-control" name="json_params[note]" rows="5">{{ $detail->json_params->note ?? '' }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- /.tab-content -->
                            </div><!-- nav-tabs-custom -->
                            <a class="btn btn-sm btn-success" href="{{ route(Request::segment(2) . '.index') }}">
                                <i class="fa fa-bars"></i> @lang('List')
                            </a>
                        </div>
                        <!-- /.box-body -->


                    </div>
                </div>
            </div>
        </form>
    </section>

@endsection

@section('script')
    <script>
        $(function() {
            var positions = @json($positions ?? []);
            var detail = @json($detail ?? []);
            $('.warehouse_id').on('change', function() {
                var warehouse_id = $(this).val();
                var _html = '<option value="">== @lang('ROOT') ==</option>';
                if (warehouse_id != '') {
                    positions.forEach(function(item) {
                        if (item.id == detail.id) {
                            return;
                        }
                        if (warehouse_id == item.warehouse_id) {
                            if (item.parent_id == null || item.parent_id == '') {
                                _html += `<option value = "` + item.id + `" > ` + item.name;
                                positions.forEach(function(sub) {
                                    if (sub.id == detail.id) {
                                        return;
                                    }
                                    if (sub.parent_id == item.id) {
                                        _html += `<option value = "` + sub.id + `" > - - ` +
                                            sub.name;
                                        // positions.forEach(function(sub_child) {
                                        //     if(sub_child.id == detail.id){return;}
                                        //     if (sub_child.parent_id == sub.id) {
                                        //         _html += `<option value = "` + sub_child.id +
                                        //             `" > - - - - ` + sub_child.name;
                                        //     }
                                        // });
                                    }
                                });
                            }
                        }
                    });
                }
                $('.parent_id').html(_html).trigger('change');
            })
        });
    </script>
@endsection
