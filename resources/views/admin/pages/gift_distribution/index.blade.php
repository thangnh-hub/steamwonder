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
            <form action="{{ route('gift_distribute') }}" method="GET">
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
                                    <a class="btn btn-default btn-sm" href="{{ route('gift_distribute') }}">
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
                <form action="{{ route('store_history') }}" method="POST">
                    <input type="text" name="course_id" value="{{ $params['course_id'] ?? '' }}" hidden>
                    @csrf
                    <div  class="box-header with-border">
                        <h3 class="box-title">@lang('Danh sách học viên')</h3>
                        @if($students->count() > 0)
                            <button type="submit" class="btn btn-success pull-right">
                            <i class="fa fa-save"></i> Lưu cấp phát quà
                            </button>
                        @endif
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
                                                @foreach ($gifts as $gift)
                                                    <li>
                                                        {{-- @if(in_array($gift->id, $val->issued_gifts))
                                                            <p style="color: green; font-size: 16px;"><i class="fa fa-check-circle"></i> {{ $gift->name }}</p>
                                                        @else
                                                            <input 
                                                                id="check_{{ $gift->id }}_{{ $val->id }}" 
                                                                type="checkbox" 
                                                                name="gifts[{{ $val->id }}][]" 
                                                                value="{{ $gift->id }}">
                                                            <label for="check_{{ $gift->id }}_{{ $val->id }}">{{ $gift->name }}</label>
                                                        @endif --}}

                                                        @if(!in_array($gift->id, $val->issued_gifts))
                                                            <input 
                                                                id="check_{{ $gift->id }}_{{ $val->id }}" 
                                                                type="checkbox" 
                                                                name="gifts[{{ $val->id }}][]" 
                                                                value="{{ $gift->id }}">
                                                            <label for="check_{{ $gift->id }}_{{ $val->id }}">{{ $gift->name }}</label>
                                                        @endif
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
                       <i class="fa fa-save"></i> Lưu cấp phát quà
                    </button>
                    @endif
                </form>
            </div>
        </div>
    </section>
@endsection
@section('script')
    <script>
        
    </script>
@endsection
