@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    {{-- <section class="content-header">
        <h1>
            @lang($module_name)
            <a class="btn btn-sm btn-warning pull-right" href="{{ route(Request::segment(2) . '.create') }}"><i
                    class="fa fa-plus"></i> @lang('Add')</a>
        </h1>
    </section> --}}

    <!-- Main content -->
    <section class="content">
        <div id="loading-notification" class="loading-notification">
            <p>@lang('Please wait')...</p>
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

        <form role="form" action="{{ route('leave.request.store') }}" method="POST"
            onsubmit="return confirm('@lang('confirm_action')')">
            @csrf
            <div class="row">
                <div class="col-lg-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title text-uppercase">@lang('Tạo mới đề xuất xin nghỉ')</h3>
                        </div>
                        @csrf
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Người đề xuất')</label>
                                        <input type="text" class="form-control" readonly value="{{ $admin_auth->name }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Người quản lý trực tiếp')</label>
                                        <input type="text" class="form-control" readonly
                                            value="{{ $admin_auth->direct_manager->name ?? '' }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Người duyệt')<small class="text-red">*</small></label>
                                        <select required name="approver_id" class="form-control" readonly
                                            style="width: 100%;">
                                            <option value="{{ $approver_user->id }}" selected>{{ $approver_user->name }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Loại')<small class="text-red">*</small></label>
                                        <select required name="is_type" class="form-control select2" style="width: 100%;">
                                            <option value="">Chọn</option>
                                            <option value="paid">Có lương</option>
                                            <option value="unpaid">Không lương </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>@lang('Từ ngày') <small class="text-red">*</small></label>
                                        <input required type="date" class="start_date form-control" name="start_date"
                                            value="">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>@lang('Đến ngày') <small class="text-red">*</small></label>
                                        <input required type="date" class="end_date form-control" name="end_date"
                                            value="">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>@lang('Tổng số ngày nghỉ') <small class="text-red">*</small></label>
                                        <input required type="number" step="0.5" min="0" class="form-control"
                                            name="total_days" value="{{ old('total_days') ?? '' }}">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>@lang('Lý do xin nghỉ')<small class="text-red">*</small></label>
                                        <textarea name="reason" required class="form-control" rows="3">{{ old('reason') ?? '' }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>@lang('Ghi chú')</label>
                                        <textarea name="note" class="form-control" rows="3">{{ old('note') ?? '' }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>@lang('Ngày dạy bù')<small>(Nếu có)</small></label>
                                        <div class="row box_day">
                                            <div class="col-md-3 d-flex-wap items_day mb-10">
                                                <input type="date" class="form-control mr-10"
                                                    name="json_params[teaching_day][]" style="width: calc(100% - 50px)">
                                                <button class="btn btn-sm btn-danger" type="button" data-toggle="tooltip"
                                                    title="@lang('Delete')" data-original-title="@lang('Delete')"
                                                    onclick="delete_day(this)">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-warning mt-15 add_day">Thêm ngày</button>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="box-footer">
                            <a class="btn btn-sm btn-success" href="{{ route('leave.request.index') }}">
                                <i class="fa fa-bars"></i> @lang('List')
                            </a>
                            <button type="submit" class="btn btn-info pull-right">
                                <i class="fa fa-save"></i> @lang('Lưu thông tin')
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>

@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('.start_date').on('change', function() {
                var start_date = $(this).val();
                $('.end_date').attr('min', start_date);
            })
            $('.end_date').on('change', function() {
                var end_date = $(this).val();
                $('.start_date').attr('max', end_date);
            })

            $('.add_day').click(function() {
                var _html = `
                <div class="col-md-3 d-flex-wap items_day mb-15">
                    <input type="date" class="form-control mr-10" name="json_params[teaching_day][]"
                        style="width: calc(100% - 50px)">
                    <button class="btn btn-sm btn-danger" type="button" data-toggle="tooltip"
                        title="@lang('Delete')" data-original-title="@lang('Delete')" onclick="delete_day(this)">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
                `;
                $('.box_day').append(_html);
            })

        });

        function delete_day(t) {
            $(t).parents('.items_day').remove();
        }
    </script>
@endsection
