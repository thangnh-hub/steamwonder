@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection

@section('style')
    <style>
        .modal-header {
            background-color: #3c8dbc;
            color: white;
        }
    </style>
@endsection

@section('content-header')
    <section class="content-header">
        <h1>
            @lang($module_name)
            <button type="button" class="btn btn-sm btn-warning pull-right" data-toggle="modal" data-target="#createDailyMenuModal">
                <i class="fa fa-plus"></i> @lang('Add')
            </button>
        </h1>
    </section>
@endsection

@section('content')
    <section class="content">
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">@lang('Filter')</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <form action="{{ route(Request::segment(2) . '.index') }}" method="GET">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Keyword') </label>
                                <input type="text" class="form-control" name="keyword" placeholder="@lang('keyword_note')"
                                    value="{{ isset($params['keyword']) ? $params['keyword'] : '' }}">
                            </div>
                        </div>
                        
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Nhóm tuổi')</label>
                                <select name="meal_age_id" class="form-control select2"style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($list_meal_age as $key => $item)
                                        <option value="{{ $item->id }}"
                                            {{ isset($params['meal_age_id']) && $params['meal_age_id'] == $item->id ? 'selected' : '' }}>{{ __($item->name) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Status')</label>
                                <select name="status" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($list_status as $key => $item)
                                        <option value="{{ $key }}"
                                            {{ isset($params['status']) && $params['status'] == $key ? 'selected' : '' }}>{{ __($item) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Filter')</label>
                                <div style="display:flex;jsutify-content:space-between;">
                                    <button type="submit" class="btn btn-primary btn-sm mr-10">@lang('Submit')</button>
                                    <a class="btn btn-default btn-sm  mr-10" href="{{ route(Request::segment(2) . '.index') }}">
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
                <h3 class="box-title">@lang('List')</h3>
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
                            <th>@lang('STT')</th>
                            <th>@lang('Mã thực đơn')</th>
                            <th>@lang('Tên thực đơn')</th>
                            <th>@lang('Các món ăn')</th>
                            <th style="width:350px;white-space: pre-line">@lang('Mô tả')</th>
                            <th>@lang('Trạng thái')</th>
                            <th>@lang('Thao tác')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rows as $row)
                            <tr class="valign-middle">
                                <td>
                                    {{ $loop->iteration + ($rows->currentPage() - 1) * $rows->perPage() }}
                                </td>
                                <td>{{ $row->code ?? '' }}</td>
                                <td>{{ $row->name ?? '' }}</td>
                                <td>
                                    @if (isset($row->menuDishes) && count($row->menuDishes) > 0)
                                        <ul >
                                            @foreach ($row->menuDishes as $dish)
                                                <li>{{ $loop->iteration }}. {{ $dish->dishes->name ?? '' }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        @lang('Chưa có món ăn nào')
                                    @endif
                                </td>
                                <td>
                                    {!! nl2br(__($row->description ?? '')) !!}
                                </td>
                                
                                <td>@lang($row->status)</td>
                                <td>
                                    <a class="btn btn-sm btn-warning" data-toggle="tooltip" title="@lang('Update')"
                                       href="{{ route('menu_dailys.edit', $row->id) }}">
                                        <i class="fa fa-pencil-square-o"></i>
                                    </a>
                
                                    <form action="{{ route('menu_dailys.destroy', $row->id) }}" method="POST"
                                          style="display:inline-block"
                                          onsubmit="return confirm('@lang('confirm_action')')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" type="submit" data-toggle="tooltip" title="@lang('Delete')">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
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
    <!-- Modal -->
    <div class="modal fade" id="createDailyMenuModal" tabindex="-1" role="dialog" aria-labelledby="createMenuLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('admin.meal-menu-daily.create-from-template') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="createMenuLabel">Tạo thực đơn hàng ngày</h4>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Ngày áp dụng <small class="text-danger">*</small></label>
                                    <input type="date" name="date" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Thực đơn mẫu <small class="text-danger">*</small></label>
                                    <select style="width:100%" name="meal_menu_planning_id" class="form-control select2" required>
                                        @foreach($menuPlannings as $plan)
                                            <option value="{{ $plan->id }}">{{ $plan->name ?? "" }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Tạo</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
@section('script')
    <script>
       
    </script>
@endsection
