@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection

@section('content-header')
    <section class="content-header">
        <h1>
            @lang($module_name)
            <a class="btn btn-sm btn-warning pull-right" href="{{ route(Request::segment(2) . '.create') }}"><i
                    class="fa fa-plus"></i> @lang('Add')</a>
        </h1>
    </section>
@endsection

@section('content')
    <section class="content">
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
                            <th>@Lang('Đơn vị gốc')</th>
                            <th>@Lang('Đơn vị đích')</th>
                            <th>@Lang('Tỷ lệ')</th>
                            <th>@Lang('Thao tác')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rows as $row)
                            <tr>
                                <td>{{ $loop->iteration + ($rows->currentPage() - 1) * $rows->perPage() }}</td>
                                <td>{{ $row->unitFrom->name ?? '' }}</td>
                                <td>{{ $row->unitTo->name ?? '' }}</td>
                                <td>1{{ $row->unitFrom->name ?? '' }} = {{ $row->ratio ?? ""}} {{ $row->unitTo->name ?? '' }}</td>
                                
                                <td>
                                    <a class="btn btn-sm btn-warning" data-toggle="tooltip" title="@lang('Update')"
                                       href="{{ route('unit_conversions.edit', $row->id) }}">
                                        <i class="fa fa-pencil-square-o"></i>
                                    </a>
                
                                    <form action="{{ route('unit_conversions.destroy', $row->id) }}" method="POST"
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

@endsection
@section('script')
    <script>
        $(document).ready(function() {
           
        });
    </script>
@endsection
