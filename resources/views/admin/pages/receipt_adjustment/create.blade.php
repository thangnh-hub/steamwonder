@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection
@section('style')
    <style>
        .item_service {
            margin-bottom: 10px;
            align-items: center;
        }
    </style>
@endsection

@section('content')
    <section class="content-header">
        <h1>
            @lang($module_name)
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
        <form role="form" action="{{ route(Request::segment(2) . '.store') }}" method="POST">
            @csrf
            <div class="box box-primary">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Học sinh') <small class="text-red">*</small></label>
                                <select required name="student_id" class="form-control select2">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($students as $val)
                                        <option value="{{ $val->id }}"
                                            {{ old('student_id') && old('student_id') == $val ? 'selected' : '' }}>
                                            {{ $val->student_code }} - {{ $val->first_name }} {{ $val->last_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Type') <small class="text-red">*</small></label>
                                <select required name="type" class="form-control select2">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($type as $key => $val)
                                        @if (!in_array($key, ['dunokytruoc', 'doisoat']))
                                            <option value="{{ $key }}"
                                                {{ old('type') && old('type') == $val ? 'selected' : '' }}>
                                                {{ __($val) }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>@lang('Số tiền') <small class="text-red">*</small></label>
                                <input type="number" name="final_amount" class="form-control" value="">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>@lang('Mô tả') </label>
                                <textarea class="form-control" name="note" rows="5"></textarea>
                            </div>
                        </div>
                        <hr>

                    </div>
                </div>
                <div class="box-footer">
                    <a class="btn btn-success btn-sm" href="{{ route(Request::segment(2) . '.index') }}">
                        <i class="fa fa-bars"></i> @lang('List')
                    </a>
                    <button type="submit" class="btn btn-primary pull-right btn-sm"><i class="fa fa-floppy-o"></i>
                        @lang('Save')</button>
                </div>
            </div>
        </form>
    </section>
@endsection

@section('script')
    <script></script>
@endsection
