<!DOCTYPE html>
<html>

<head>
    <title>Nhắc nhở có {{ count($students) }} học viên đến hạn công nợ</title>
</head>

<body>
    <h2 style="text-align: center;">Danh sách học viên đến hạn công nợ</h2>

    <table border="1" width="100%" cellspacing="0" cellpadding="2">
        <thead>
            <tr style="background-color: #007bff; color: #fff;">
                <th>STT</th>
                <th>Mã học viên</th>
                <th>Họ tên</th>
                <th>Lớp học</th>
                <th>Lớp đang học</th>
                <th>Khóa học</th>
                <th>Ngày học chính thức</th>
                <th>Ngày đến hạn công nợ</th>
                <th>Xem</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($students as $student)
                <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <td>
                        {{ $student->admin_code }}
                    </td>
                    <td>
                        {{ $student->name }}
                    </td>
                    <td>
                        @if (isset($student->classs))
                            <ul style="margin: 0;padding-left: 15px;">
                                @foreach ($student->classs as $i)
                                    <li>
                                        {{ $i->name }}
                                        ({{ __($i->pivot->status ?? '') }})
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </td>
                    <td>
                        @if (isset($student->classs))
                            <ul style="margin: 0;padding-left: 15px;">
                                @foreach ($student->classs as $i)
                                    @if ($i->status == 'dang_hoc')
                                        <li>{{ $i->name }}</li>
                                    @endif
                                @endforeach
                            </ul>
                        @endif
                    </td>
                    <td>{{ $student->course->name ?? '' }}</td>
                    <td>
                        {{ $student->day_official != '' ? date('d-m-Y', strtotime($student->day_official)) : '' }}
                    </td>
                    <td>
                        {{ $student->day_official != '' ? Carbon\Carbon::parse($student->day_official)->addDays(150)->format('d-m-Y') : '' }}
                    </td>
                    <td>
                        <a href="{{ route('students.show', $student->id) }}">
                            Chi tiết
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
