@extends('admin.layouts.app')


@section('title')
    @lang($module_name)
@endsection

@section('style')
    <style>
        .mr-2{
            margin-right: 10px;
        }
        .table_leson .select2-container {
            width: 100% !important;
        }
        .d-flex{
            display: flex;
        } 
        .align-items-center{
            align-items: center;
        }
        .overflow-auto{
            width: 100%;
            overflow-x: auto;
        }
        .overflow-auto::-webkit-scrollbar{
          width: 5px !important;
        }
        .overflow-auto::-webkit-scrollbar-track {
          background: #f1f1f1;border-radius: 10px;
        }

        .overflow-auto::-webkit-scrollbar-thumb {
          background: rgb(107, 144, 218);border-radius: 10px;
        }
        .table_leson{
            width: 1500px;
            max-width: unset;
        }
        .table_leson td:first-child{
            width: 190px;
        }
        .table_leson thead{
            background: rgb(107, 144, 218);
            color: #fff
        }
    </style>
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
                            <h3 class="box-title">@lang('Update form')</h3>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        
                        <div class="box-body">
                            <!-- Custom Tabs -->
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a href="#tab_1" data-toggle="tab">
                                            <h5>Thông tin chính <span class="text-danger">*</span></h5>
                                        </a>
                                    </li>
                                    @if($detail->status != App\Consts::SCHEDULE_STATUS['dadiemdanh'])
                                    <button type="submit" class="btn btn-info btn-sm pull-right">
                                        <i class="fa fa-save"></i> @lang('Save')
                                    </button>
                                    @else
                                    <button type="button" class="btn btn-danger btn-sm pull-right">
                                        @lang(App\Consts::SCHEDULE_STATUS['dadiemdanh'])
                                    </button>
                                    @endif
                                </ul>

                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_1">
                                        <div class="d-flex-wap">

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Class') <small class="text-red">*</small></label>
                                                    <input disabled type="text" class="form-control" 
                                                        placeholder="@lang('Class')" value="{{  $detail->class->name }}"
                                                        >
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Date') <small class="text-red">*</small></label>
                                                    <input required type="date" name="date" class="form-control" 
                                                        placeholder="@lang('Start date')" value="{{ \Carbon\Carbon::parse($detail->date)->format('Y-m-d') }}"
                                                        >
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Room') <small class="text-red">*</small></label>
                                                    <select required name="room_id" class="form-control select2" >
                                                        @foreach ($room as $key => $val)
                                                            <option {{ isset($detail->room_id) && $detail->room_id == $val->id ? 'selected' : '' }} value="{{ $val->id }}">
                                                                {{ $val->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Teacher') <small class="text-red">*</small></label>
                                                    <select required name="teacher_id" class="form-control select2">
                                                        <option value="">@lang('Teacher')</option>
                                                        @foreach ($teacher as $val)
                                                            <option {{ isset($detail->teacher_id) && $detail->teacher_id == $val->id ? 'selected' : '' }} value="{{ $val->id }}">
                                                                {{ $val->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Period')<small class="text-red">*</small></label>
                                                    <select required name="period_id" class=" form-control select2">
                                                        <option value="">@lang('Period')</option>
                                                        @foreach ($period as $val)
                                                            <option {{ isset($detail->period_id) && $detail->period_id == $val->id ? 'selected' : '' }} value="{{ $val->id }}">
                                                                {{ $val->iorder }} ({{ $val->start_time }} - {{ $val->end_time }})</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Status')<small class="text-red">*</small></label>
                                                    <select required name="status" class=" form-control select2">
                                                        <option value="">@lang('Status')</option>
                                                        @foreach ( App\Consts::SCHEDULE_STATUS as $k => $val)
                                                            <option {{ isset($detail->status) && $detail->status == $k? 'selected' : '' }} value="{{ $k}}">
                                                                {{ App\Consts::SCHEDULE_STATUS[$k] }} </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                           
                                        </div>
                                    </div>
                                </div>
                            </div><!-- /.tab-content -->
                        </div><!-- nav-tabs-custom -->

                    </div>
                </div>
            </div>
        </form>
    </section>
@endsection

@section('script')
    <script>
        function _add_file(th){
            var i=$(th).data('target');
            var _html='<div class="input-group mt-10 position-relative parent-file"><span class="input-group-btn"><button type="button" data-input="image_'+$.now()+'" onclick="_lfm(this)" class="btn btn-primary lfm" data-type="file"><i class="fa fa-picture-o"></i> @lang('Tệp')</button></span><input id="image_'+$.now()+'" class="form-control" type="text" name="lesson['+i+'][file][]" placeholder="@lang('link')..."><div onclick="_delete_file(this)" class="border-50 delete_file_lesson position-absolute"><button type="button" class=" close " data-dismiss="alert" aria-hidden="true">&times;</button></div></div>';
            $(th).parents('td').find('.document_file').append(_html);
        }
        function _delete_file(th){
             $(th).parents('.parent-file').remove();
        }
    </script>
@endsection
