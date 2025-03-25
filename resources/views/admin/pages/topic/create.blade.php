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
                                <div class="tab_offline">
                                    <div class="tab-pane active">
                                        <div class="">
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
                                                    <label>@lang('Số câu hỏi sẽ thi') <small class="text-red">*</small></label>
                                                    <input type="number" class="form-control" name="question_exam"
                                                        placeholder="@lang('Số câu hỏi sẽ thi')" value="{{ old('question_exam') }}"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Loại đề') <small class="text-red">*</small></label>
                                                    <select name="type" class=" form-control select2 topic_type">
                                                        @foreach ($type as $key => $val)
                                                            <option value="{{ $key }}"
                                                                {{ isset($detail->type) && $detail->type == $val ? 'checked' : '' }}>
                                                                @lang($val)</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Status')</label>
                                                    <select name="status" class=" form-control select2">
                                                        @foreach ($status as $key => $val)
                                                            <option value="{{ $key }}"
                                                                {{ isset($detail->status) && $detail->status == $val ? 'checked' : '' }}>
                                                                @lang($val)</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="form-group">
                                                        <label>@lang('Content')</label>
                                                        <textarea name="content" class="form-control" id="content_vi">{{ old('content') }}</textarea>
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
@section('script')
    <script>
        CKEDITOR.replace('content_vi', ck_options);
    </script>
@endsection