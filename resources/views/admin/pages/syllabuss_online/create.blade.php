@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection

@section('style')
    <style>
        .select2-container{
            width: 100% !important;
        }
        .hidden{
            display: none;
        }
        .mb-10{
            margin-bottom: 10px
        }
        .pl-0{
            padding-left: 0px !important
        }
        textarea {
            resize: none;
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

        <form role="form" action="{{ route(Request::segment(2) . '.store') }}" method="POST" id="form_product">
            @csrf
            
            <div class="row">
                <div class="col-lg-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Create form')</h3>
                            <button type="submit" class="btn btn-info btn-sm pull-right">
                                <i class="fa fa-save"></i> @lang('Save')
                            </button>
                        </div>
                        <div class="box-body">
                            <!-- Custom Tabs -->
                            <div class="nav-tabs-custom">
                                <div class="tab_offline">
                                    <div class="tab-pane active">
                                        <div class="">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Loại chương trình') <small class="text-red">*</small></label>
                                                    <select required name="type" class="form-control select2 type_sylabus">
                                                        <option value="elearning">Elearning</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Title') <small class="text-red">*</small></label>
                                                    <input type="text" class="form-control" name="name"
                                                        placeholder="@lang('Title')" value="{{ old('name') }}"
                                                        required>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Approve')</label>
                                                    <select name="is_approve" required class=" form-control select2">
                                                        @foreach ($approve as $key => $val)
                                                            <option {{ old('is_approve') == $key ? 'selected' : '' }} value="{{ $key }}">
                                                                @lang($val)</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Level')<small class="text-red">*</small></label>
                                                    <select name="level_id" required class=" form-control select2">
                                                            <option value="">@lang('Level')</option>
                                                        @foreach ($parents as $val)
                                                            <option {{ old('level_id') == $val->id ? 'selected' : '' }} value="{{ $val->id }}">
                                                                {{ $val->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>

@endsection