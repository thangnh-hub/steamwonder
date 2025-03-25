@extends('admin.layouts.pdf')
@section('title')
  Bảng điểm theo lớp
@endsection
@section('content')
  @if (isset($this_class))
    <div class="table-container">
      <h3 class="box-title">
        @lang('Bảng điểm học viên')
        - Lớp {{ $this_class['name'] }}
        - Giáo viên {{ $teacher['name'] }}
      </h3>
      @if (count($rows) > 0)
        <table class="content">
          <tbody>
            <tr>
              <th rowspan="2" style="text-align: center; width: 5%">@lang('Mã')</th>
              <th rowspan="2" style="text-align: center; width: 15%">@lang('Student')</th>
              <th rowspan="2" style="text-align: center; width: 15%">@lang('Lớp')</th>
              <th colspan="5" style="text-align: center; width: 40%">@lang('Điểm') </th>
              <th rowspan="2" style="text-align: center; width: 15%">@lang('Nhận xét')</th>
              <th rowspan="2" style="text-align: center; width: 10%">@lang('Xếp loại')</th>
            </tr>
            <tr>
              <th style="text-align: center;width: 8%">@lang('Nghe') </th>
              <th style="text-align: center;width: 8%">@lang('Nói') </th>
              <th style="text-align: center;width: 8%">@lang('Đọc') </th>
              <th style="text-align: center;width: 8%">@lang('Viết') </th>
              <th style="text-align: center;width: 8%">@lang('TB')</th>
            </tr>

            @foreach ($rows as $row)
              <tr class="row">
                <td rowspan="{{ isset($row['userClasses']) ? count($row['userClasses']) : 1 }}">
                  {{ $row['student']['admin_code'] ?? '' }}
                </td>
                <td rowspan="{{ isset($row['userClasses']) ? count($row['userClasses']) : 1 }}">
                  {{ $row['student']['name'] ?? '' }}

                </td>
                @if (isset($row['userClasses']))
                  @foreach ($row['userClasses'] as $userClass)
                    {!! $loop->index > 0 ? '<tr class="row">' : '' !!}
                    <td>
                      {{ $userClass['class']['name'] ?? '' }}
                      ({{ __($userClass['status']) }})
                    </td>
                    <td>{{ $userClass['score']['score_listen'] ?? '' }}</td>
                    <td>{{ $userClass['score']['score_speak'] ?? '' }}</td>
                    <td>{{ $userClass['score']['score_read'] ?? '' }}</td>
                    <td>{{ $userClass['score']['score_write'] ?? '' }}</td>
                    <td>{{ $userClass['score']['json_params']['score_average'] ?? '' }}</td>
                    <td>{{ $userClass['score']['json_params']['note'] ?? '' }}</td>
                    <td>
                      {{ isset($userClass['score']['status']) ? App\Consts::ranked_academic_total[$userClass['score']['status']] ?? $userClass['score']['status'] : 'Chưa xác định' }}
                    </td>
                    {!! $loop->index > 0 ? '</tr>' : '' !!}
                  @endforeach
                @else
                  <td>
                    {{ $this_class['name'] ?? '' }}
                  </td>
                  <td>
                    {{ $row['score_listen'] ?? '' }}
                  </td>
                  <td>
                    {{ $row['score_speak'] ?? '' }}
                  </td>
                  <td>
                    {{ $row['score_read'] ?? '' }}
                  </td>
                  <td>
                    {{ $row['score_write'] ?? '' }}
                  </td>
                  <td>
                    {{ $row['json_params']['score_average'] ?? '0' }}
                  </td>
                  <td>
                    {{ $row['json_params']['note'] ?? '' }}
                  </td>
                  <td>
                    {{ $row['status'] != '' ? App\Consts::ranked_academic_total[$row['status']] ?? $row['status'] : 'Chưa xác định' }}
                  </td>
                @endif
              </tr>
            @endforeach
          </tbody>
        </table>
      @endif
    </div>
  @endif

@endsection
