@extends('admin.layouts.pdf')
@section('title')
    Nhận xét đánh giá theo lớp
@endsection
@section('content')
    @if (isset($this_class))
        <h3 class="box-title">@lang('Lớp'):{{ $this_class['name'] }}
            - Giảng viên: {{ $teacher['name'] ?? '' }}
            - Buổi: {{ optional(\Carbon\Carbon::parse($schedule['date']))->format('l d/m/Y') }}</h3>

        @if (count($rows) > 0)
            <table class="content">
                <thead>
                    <tr>
                        <th>@lang('Order')</th>
                        <th>@lang('Class')</th>
                        <th>@lang('Student')</th>
                        <th>@lang('Home Work')</th>
                        <th>@lang('Updated at')</th>
                        <th>@lang('Status')</th>
                        <th>@lang('Note status')</th>
                        <th>@lang('Ghi chú nhận xét (GV nhập)')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rows as $row)
                        @php
                            $class = collect($list_class)->first(function ($item, $key) use ($row) {
                                return $item['id'] == $row['class_id'];
                            });
                            $student = collect($students)->first(function ($item, $key) use ($row) {
                                return $item['id'] == $row['user_id'];
                            });

                        @endphp
                        <tr class="row">
                            <td>
                                {{ $loop->index + 1 }}
                            </td>
                            <td>
                                {{ $class['name'] ?? '' }}</td>
                            <td>
                                {{ $student['name'] ?? '' }}({{ $student['admin_code'] ?? '' }})
                            </td>
                            <td>
                                {{ $row['is_homework'] != '' ? __($is_homework[$row['is_homework']]) : 'Chưa cập nhật' }}
                            </td>
                            <td>
                                {{ $row['updated_at'] ?? '' }}
                            </td>
                            <td>
                                {{ __($status[$row['status']]) }}
                            </td>
                            <td>
                                {{ $row['json_params']['value'] ?? '' }}
                            </td>
                            <td>
                                {{ $row['note_teacher'] ?? '' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

    @endif

@endsection
