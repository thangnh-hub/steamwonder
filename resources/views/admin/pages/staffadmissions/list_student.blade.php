@if (count($rows) > 0)
    <div class="box-body no-padding">
        <div class="table-responsive mailbox-messages">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th style="width: 40px">#</th>
                        <th>@lang('Student code')</th>
                        <th>@lang('Name')</th>
                        <th>@lang('Lớp')</th>
                        <th>@lang('Status')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rows as $item)
                        <tr>
                            <td>{{ $loop->index + 1 }}.</td>
                            <td>
                                <a target="_blank" class="btn btn-sm" data-toggle="tooltip" title="@lang('Xem chi tiết')"
                                    data-original-title="@lang('Xem chi tiết')"
                                    href="{{ route('students.show', $item->id) }}">
                                    {{ $item->admin_code }}
                                </a>
                            </td>
                            <td>{{ $item->name ?? '' }}</td>
                            <td>
                                @if (isset($item->classs))
                                    <ul>
                                        @foreach ($item->classs as $i)
                                            <li>{{ $i->name }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </td>
                            <td>@lang($item->status_study_name ?? 'Chưa cập nhật')</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="box-footer clearfix">
        <div class="row">
            <div class="col-sm-5">
                Tìm thấy {{ $rows->total() }} kết quả
            </div>
            <div class="col-sm-7">
                {{ $rows->withQueryString()->links('admin.pagination.staffadmissions', ['adminId' => $admin->id]) }}
            </div>
        </div>
    </div>
@else
    <div class="alert alert-warning alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        @lang('Không tìm thấy dữ liệu!')
    </div>
@endif
