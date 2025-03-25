@extends('admin.layouts.pdf')
@section('title')
    Nhận xét đánh giá theo lớp
@endsection
@section('content')
    @if (isset($this_class))
        <h3 class="box-title">
            @lang('Danh sách nhận xét đánh giá lớp')
            - Lớp {{ $this_class['name'] }}
            - Giáo viên {{ $teacher['name'] }}
            - Từ ngày {{ $from_date ?? '' }} đến {{ $to_date ?? '' }}
        </h3>
        @if (count($rows) > 0)
            <table class="content">
                <thead>
                    <tr>
                        <th>@lang('Order')</th>
                        <th>@lang('Mã học viên')</th>
                        <th>@lang('Student')</th>
                        <th>@lang('Học lực')</th>
                        <th>@lang('Ý thức')</th>
                        <th>@lang('Kiến thức')</th>
                        <th>@lang('Kỹ năng')</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($rows as $row)
                        <tr class="row">
                            <td>
                                {{ $loop->index + 1 }}
                            </td>

                            <td>{{ $row['admin_code'] ?? '' }}</td>
                            <td>
                                {{ $row['admin_name'] ?? '' }}
                            </td>
                            <td>
                                {!! nl2br($row['json_params']['ability'] ?? '') !!}
                            </td>
                            <td>
                                {!! nl2br($row['json_params']['consciousness'] ?? '') !!}
                            </td>
                            <td>
                                {!! nl2br($row['json_params']['knowledge'] ?? '') !!}
                            </td>
                            <td>
                                {!! nl2br($row['json_params']['skill'] ?? '') !!}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

    @endif

@endsection
