@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection

@section('content-header')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang($module_name)
        </h1>
    </section>
@endsection

@section('content')

    <!-- Main content -->
    <section class="content">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">@lang('Reviews list')</h3>
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
                        @lang('No record found')
                    </div>
                @else
                <table class="table table-hover table-bordered">
                    <thead>
                      <tr>
                        <th>@lang('Fullname')</th>
                        <th>@lang('Email')</th>
                        <th>@lang('Comment')</th>
                        <th>@lang('Created at')</th>
                        <th>@lang('Updated at')</th>
                        <th>@lang('Status')</th>
                        <th>@lang('Action')</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($rows as $row)
                        <form action="{{ route(Request::segment(2) . '.destroy', $row->id) }}" method="POST"
                          onsubmit="return confirm('@lang('confirm_action')')">
                          <tr class="valign-middle">
                            <td>
                              <strong style="font-size: 14px;">{{ $row->name }}</strong>
                            </td>
                            <td>
                              {{ $row->email }}
                            </td>
                            <td>
                              {{ Str::limit($row->comment, 100) }}
                            </td>
                            <td>
                              {{ $row->created_at }}
                            </td>
                            <td>
                              {{ $row->updated_at }}
                            </td>
                            <td>
                              @lang($row->status)
                            </td>
                            <td>
                              <a class="btn btn-sm btn-warning" data-toggle="tooltip" title="@lang('Update')"
                                data-original-title="@lang('Update')"
                                href="{{ route(Request::segment(2) . '.edit', $row->id) }}">
                                <i class="fa fa-pencil-square-o"></i>
                              </a>
                              @csrf
                              @method('DELETE')
                              <button class="btn btn-sm btn-danger" type="submit" data-toggle="tooltip" title="@lang('Delete')"
                                data-original-title="@lang('Delete')">
                                <i class="fa fa-trash"></i>
                              </button>
                            </td>
                          </tr>
                        </form>
                      @endforeach
                    </tbody>
                  </table>
                @endif
            </div>

            @if ($rows->hasPages())
                <div class="box-footer clearfix">
                    {{ $rows->withQueryString()->links('pagination.default') }}
                </div>
            @endif

        </div>
    </section>
@endsection
