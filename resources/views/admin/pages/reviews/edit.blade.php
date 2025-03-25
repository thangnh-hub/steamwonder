@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang($module_name)
            {{-- <a class="btn btn-sm btn-warning pull-right" href="{{ route(Request::segment(2) . '.create') }}"><i
                    class="fa fa-plus"></i> @lang('Add')</a> --}}
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
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">@lang('Detail review')</h3>
            </div>

            <form role="form" action="{{ route(Request::segment(2) . '.update', $detail->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="box-body">

                    <div class="form-horizontal">
                        <div class="form-group">
                            <label class="col-sm-3 text-right text-bold">@lang('Fullname'):</label>
                            <label class="col-sm-9 col-xs-12">{{ $detail->name ?? '' }}</label>
                        </div>


                        <div class="form-group">
                            <label class="col-sm-3 text-right text-bold">@lang('Email'):</label>
                            <label class="col-sm-9 col-xs-12">
                                {{ $detail->email ?? '' }}
                            </label>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 text-right text-bold">@lang('Content note'):</label>
                            <label class="col-sm-9 col-xs-12">{{ $detail->comment ?? '' }}</p>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 text-right text-bold">@lang('Title post'):</label>
                            <label class="col-sm-9 col-xs-12"><a href="{{route('frontend.page', ['taxonomy' => $posts->alias ?? ''])}}" target="_blank" rel="noopener noreferrer">{{ $posts->name ?? '' }}</a></p>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 text-right text-bold">@lang('Rating'):</label>
                            <div class="rating col-sm-9">
                                <div class="star star-{{$detail->rating}} ">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 text-right text-bold">@lang('Status'):</label>
                            <div class="col-sm-6 col-xs-12 ">
                                <div class="form-control">
                                    @foreach (App\Consts::STATUS as $key => $value)
                                        <label>
                                            <input type="radio" name="status" value="{{ $key }}"
                                                {{ $detail->status == $key ? 'checked' : '' }}>
                                            <small class="mr-15">{{ __($value) }}</small>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="box-footer">
                    <a class="btn btn-success btn-sm" href="{{ route(Request::segment(2) . '.index') }}">
                        <i class="fa fa-bars"></i> @lang('List')
                    </a>
                    <button type="submit" class="btn btn-primary pull-right btn-sm">
                        <i class="fa fa-floppy-o"></i>
                        @lang('Save')
                    </button>
                </div>
            </form>
        </div>
    </section>
@endsection
