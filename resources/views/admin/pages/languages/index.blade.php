@extends('admin.layouts.app')

@section('title')
  @lang($module_name)
@endsection

@section('content-header')
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      @lang($module_name)
      <a class="btn btn-sm btn-warning pull-right" href="{{ route(Request::segment(2) . '.create') }}"><i
          class="fa fa-plus"></i> @lang('Add')</a>
    </h1>
  </section>
@endsection
@section('style')
  <style>
    tr .set-language-default {
      display: none;
    }

    tr:hover .set-language-default {
      display: block;
    }
  </style>
@endsection
@section('content')

  <!-- Main content -->
  <section class="content">
    <div class="box">
      <div class="box-header">
        <h3 class="box-title">@lang('Widget list')</h3>
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
                <th>@lang('Name')</th>
                <th>@lang('Locale')</th>
                <th>@lang('Code')</th>
                <th>@lang('Is default')</th>
                <th>@lang('Order')</th>
                <th>@lang('Updated at')</th>
                {{-- <th>@lang('Status')</th> --}}
                <th>@lang('Action')</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($rows as $row)
                <form action="{{ route(Request::segment(2) . '.destroy', $row->id) }}" method="POST"
                  onsubmit="return confirm('@lang('confirm_action')')">
                  <tr class="valign-middle">
                    <td>
                      <a href="{{ route(Request::segment(2) . '.edit', $row->id) }}">
                        <strong style="font-size: 14px">{{ $row->lang_name }}</strong>
                      </a>
                    </td>
                    <td>
                      {{ $row->lang_locale }}
                    </td>
                    <td>
                      {{ $row->lang_code }}
                    </td>
                    <td>
                      @if (!$row->is_default)
                        <a class="set-language-default" data-toggle="tooltip"
                          data-original-title="{{ __('Choose') . ' ' . $row->lang_name . ' ' . __('as default language') }}"
                          href="javascript:void(0);" style="font-size: 20px;" data-id="{{ $row->id }}" data-name="{{ $row->lang_name }}">
                          <i class="fa fa-star"></i>
                        </a>
                      @else
                        <i class="fa fa-star text-success" data-id="{{ $row->id }}"
                          data-name="{{ $row->lang_name }}" style="font-size: 20px;"></i>
                      @endif

                    </td>
                    <td>
                      {{ $row->iorder }}
                    </td>
                    <td>
                      {{ $row->updated_at }}
                    </td>
                    {{-- <td>
                      @lang($row->status)
                    </td> --}}
                    <td>
                      <a class="btn btn-sm btn-warning" data-toggle="tooltip" title="@lang('Update')"
                        data-original-title="@lang('update')"
                        href="{{ route(Request::segment(2) . '.edit', $row->id) }}">
                        <i class="fa fa-pencil-square-o"></i>
                      </a>
                      @csrf
                      @method('DELETE')
                      <button class="btn btn-sm btn-danger" type="submit" data-toggle="tooltip"
                        title="@lang('Delete')" data-original-title="@lang('delete')">
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

@section('script')
  <script>
    $('.set-language-default').click(function() {
      $('#loading').show();
      let _id = $(this).data('id');
      $.ajax({
          url: '{{ route('languages.set_default') }}',
          type: 'POST',
          data: {
            _token: '{{ csrf_token() }}',
            id: _id
          },
        })
        .done(function(data) {
          $('#loading').hide();
          if (data.error == 0) {
            location.reload();
          } else {
            alert(data.msg);
            location.reload();
          }
        });
    });
  </script>
@endsection
