<h5>{{ $mealAge->name }} - Ngày {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }} - Khu vực {{ $area_name }}</h5>
<br>

<div class="table-wrapper">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Lớp</th>
                <th>Số suất ăn</th>
                <th>Thực đơn</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($classes as $class)
                <tr>
                    <td>{{ $class->name }}</td>
                    <td>{{ $class->attendance_count }}</td>
                    <td>
                        <a target="_blank" href="{{ route('menu_dailys.edit', $menu_daily_id) }}">
                            @lang('Link thực đơn')
                        </a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="3">Không có lớp nào.</td></tr>
            @endforelse
        </tbody>
    </table>                    
</div>

