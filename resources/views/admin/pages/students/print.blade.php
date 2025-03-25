<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Báo cáo học viên {{ $detail->name??"" }}</title>
    <style>
        @media print {
            table {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
    </style>
</head>
    <body onload="window.print()">
    <div>
        <div style="text-align:center;">
            <table cellspacing="0" cellpadding="0"
                style="margin-right:auto; margin-left:auto; border:0.75pt solid #000000; border-collapse:collapse;">
                <tbody>
                    <tr style="height:98.5pt;">
                        <td
                            style="width:492.95pt; border-right-style:solid; border-right-width:0.75pt; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top;">
                            <p
                                style="margin-top:0pt; margin-bottom:0pt; text-align:center; line-height:150%; font-size:16pt;">
                                <strong><span style="font-family:'Times New Roman';">B&Aacute;O C&Aacute;O T&Igrave;NH
                                        H&Igrave;NH HỌC TẬP CỦA HỌC VI&Ecirc;N</span></strong></p>
                            <p style="margin-top:0pt; margin-bottom:0pt; line-height:150%; font-size:12pt;"><span
                                    style="font-family:'Times New Roman';">M&atilde; học vi&ecirc;n:
                                    {{ $detail->admin_code }}..</span></p>
                            <p style="margin-top:0pt; margin-bottom:0pt; line-height:150%; font-size:12pt;"><span
                                      style="font-family:'Times New Roman';">
                                     Kh&oacute;a:
                                      {{ $course }}</span></p>
                            <p style="margin-top:0pt; margin-bottom:0pt; line-height:150%; font-size:12pt;"><span
                                    style="font-family:'Times New Roman';">Họ v&agrave; t&ecirc;n học vi&ecirc;n:
                                    <strong>{{ $detail->name??"" }}</strong></span></p>
                            <p style="margin-top:0pt; margin-bottom:0pt; line-height:150%; font-size:12pt;"><span
                                    style="font-family:'Times New Roman';">Loại hợp đồng:
                                    {{ $detail->json_params->contract_type??"" }}</span></p>
                            <p style="margin-top:0pt; margin-bottom:0pt; line-height:150%; font-size:12pt;"><span
                                    style="font-family:'Times New Roman';">Lớp hiện tại:
                                    {{ $class->name??"" }} ...Tr&igrave;nh độ hiện tại: {{ $class->level->name??"" }}</span></p>
                            
                            <p style="margin-top:0pt; margin-bottom:0pt; line-height:150%; font-size:12pt;"><span
                                    style="font-family:'Times New Roman';">Thời gian b&aacute;o c&aacute;o: Từ
                                    ng&agrave;y .../.../... đến hết ng&agrave;y .../.../....</span></p>
                        </td>
                        <td
                            style="width:164.7pt; border-left-style:solid; border-left-width:0.75pt; padding-right:5.03pt; padding-left:5.03pt; vertical-align:middle;">
                            <img style="width:164.7pt;height:185px;object-fit: cover;" src="{{ $detail->avatar??url('themes/admin/img/no_image.jpg') }}" alt="">
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <p style="margin-top:0pt; margin-bottom:8pt;"><span style="font-family:'Times New Roman';">&nbsp;</span></p>
        <div style="text-align:center;">
            <table cellspacing="0" cellpadding="0"
                style="margin-right:auto; margin-left:auto; border-collapse:collapse;">
                <tbody>
                    <tr>
                        <td style="width:70.2pt; padding-right:5.4pt; padding-left:5.4pt; vertical-align:top;">
                            <p style="margin-top:0pt; margin-bottom:0pt; text-align:right; font-size:13pt;">
                                <strong><em><u><span style="font-family:'Times New Roman';">K&iacute;nh
                                                gửi:</span></u></em></strong></p>
                        </td>
                        <td style="width:286.2pt; padding-right:5.4pt; padding-left:5.4pt; vertical-align:top;">
                            <p style="margin-top:0pt; margin-bottom:0pt; line-height:150%; font-size:13pt;">
                                <strong><span style="font-family:'Times New Roman';">- Qu&yacute; phụ huynh học
                                        vi&ecirc;n {{ $detail->name??"" }}</span></strong></p>
                            <p style="margin-top:0pt; margin-bottom:0pt; line-height:150%; font-size:13pt;">
                                <strong><span style="font-family:'Times New Roman';">- Ban tuyển sinh.</span></strong>
                            </p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <p
            style="margin-top:10pt; margin-bottom:0pt; text-indent:36pt; text-align:justify; line-height:108%; font-size:12pt;">
            <span style="font-family:'Times New Roman';">Đầu ti&ecirc;n, C&ocirc;ng ty TNHH DWN Việt Nam gửi lời cảm ơn
                tới Qu&yacute; phụ huynh, Ban tuyển sinh đ&atilde; tin tưởng v&agrave; hợp t&aacute;c c&ugrave;ng
                C&ocirc;ng ty trong suốt thời gian qua.</span></p>
        <p
            style="margin-top:0pt; margin-bottom:0pt; text-indent:18pt; text-align:justify; line-height:108%; font-size:12pt;">
            <span style="font-family:'Times New Roman';">C&ocirc;ng ty TNHH DWN Việt Nam xin tr&acirc;n trọng gửi tới
                Qu&yacute; phụ huynh v&agrave; Ban tuyển sinh b&aacute;o c&aacute;o t&igrave;nh h&igrave;nh học tập của
                học vi&ecirc;n  <strong>{{ $detail->name??"" }}</strong>, cụ thể như sau:</span></p>
        <ol type="1" style="margin:0pt; padding-left:0pt;margin-top:20px">
            <li
                style="margin-left:32pt; line-height:108%; padding-left:4pt; font-family:'Times New Roman'; font-size:12pt; font-weight:bold;">
                B&aacute;o c&aacute;o qu&aacute; tr&igrave;nh học tập</li>
        </ol>
        <table cellspacing="0" cellpadding="0"
            style="width:704.5pt; margin-left:12pt; border:0.75pt solid #000000; border-collapse:collapse;margin-top:20px;margin-bottom:20px">
            <tbody>
                <tr style="height:14.8pt;">
                    <td rowspan="2"
                        style="width:20.95pt; border-right-style:solid; border-right-width:0.75pt; border-bottom-style:solid; border-bottom-width:0.75pt; padding-right:5.03pt; padding-left:5.03pt; vertical-align:middle; background-color:#e2efd9;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:11pt;"><strong><span
                                    style="font-family:'Times New Roman';">STT</span></strong></p>
                    </td>
                    <td rowspan="2"
                        style="width:52.8pt; border-right-style:solid; border-right-width:0.75pt; border-left-style:solid; border-left-width:0.75pt; border-bottom-style:solid; border-bottom-width:0.75pt; padding-right:5.03pt; padding-left:5.03pt; vertical-align:middle; background-color:#e2efd9;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:11pt;"><strong><span
                                    style="font-family:'Times New Roman';">Lớp</span></strong></p>
                    </td>
                    <td rowspan="2"
                        style="width:88.25pt; border-right-style:solid; border-right-width:0.75pt; border-left-style:solid; border-left-width:0.75pt; border-bottom-style:solid; border-bottom-width:0.75pt; padding-right:5.03pt; padding-left:5.03pt; vertical-align:middle; background-color:#e2efd9;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:11pt;"><strong><span
                                    style="font-family:'Times New Roman';">Học viên</span></strong></p>
                    </td>
                    <td rowspan="2"
                        style="width:88.25pt; border-right-style:solid; border-right-width:0.75pt; border-left-style:solid; border-left-width:0.75pt; border-bottom-style:solid; border-bottom-width:0.75pt; padding-right:5.03pt; padding-left:5.03pt; vertical-align:middle; background-color:#e2efd9;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:11pt;"><strong><span
                                    style="font-family:'Times New Roman';">Tr&igrave;nh độ</span></strong></p>
                    </td>
                    <td rowspan="2"
                        style="width:88.25pt; border-right-style:solid; border-right-width:0.75pt; border-left-style:solid; border-left-width:0.75pt; border-bottom-style:solid; border-bottom-width:0.75pt; padding-right:5.03pt; padding-left:5.03pt; vertical-align:middle; background-color:#e2efd9;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:11pt;"><strong><span
                                    style="font-family:'Times New Roman';">Chương trình</span></strong></p>
                    </td>
                    <td colspan="5"
                        style="width:227.55pt; border-right-style:solid; border-right-width:0.75pt; border-left-style:solid; border-left-width:0.75pt; border-bottom-style:solid; border-bottom-width:0.75pt; padding-right:5.03pt; padding-left:5.03pt; vertical-align:middle; background-color:#e2efd9;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:11pt;"><strong><span
                                    style="font-family:'Times New Roman';">Điểm</span></strong></p>
                    </td>
                    <td colspan="3"
                        style="width:227.55pt; border-right-style:solid; border-right-width:0.75pt; border-left-style:solid; border-left-width:0.75pt; border-bottom-style:solid; border-bottom-width:0.75pt; padding-right:5.03pt; padding-left:5.03pt; vertical-align:middle; background-color:#e2efd9;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:11pt;"><strong><span
                                    style="font-family:'Times New Roman';">Điểm danh</span></strong></p>
                    </td>
                    <td colspan="3"
                        style="width:227.55pt; border-right-style:solid; border-right-width:0.75pt; border-left-style:solid; border-left-width:0.75pt; border-bottom-style:solid; border-bottom-width:0.75pt; padding-right:5.03pt; padding-left:5.03pt; vertical-align:middle; background-color:#e2efd9;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:11pt;"><strong><span
                                    style="font-family:'Times New Roman';">Bài tập</span></strong></p>
                    </td>
                    <td rowspan="2"
                        style="width:189.6pt; border-left-style:solid; border-left-width:0.75pt; border-bottom-style:solid; border-bottom-width:0.75pt; padding-right:5.03pt; padding-left:5.03pt; vertical-align:middle; background-color:#e2efd9;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:11pt;"><strong><span
                                    style="font-family:'Times New Roman';">Nhận x&eacute;t</span></strong></p>
                    </td>
                </tr>
                <tr style="height:25.5pt;">
                    <td
                        style="width:23.85pt; border-style:solid; border-width:0.75pt; padding-right:5.03pt; padding-left:5.03pt; vertical-align:middle;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:11pt;"><span
                                style="font-family:'Times New Roman';">Nghe</span></p>
                    </td>
                    <td
                        style="width:27.2pt; border-style:solid; border-width:0.75pt; padding-right:5.03pt; padding-left:5.03pt; vertical-align:middle;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:11pt;"><span
                                style="font-family:'Times New Roman';">N&oacute;i</span></p>
                    </td>
                    <td
                        style="width:30.65pt; border-style:solid; border-width:0.75pt; padding-right:5.03pt; padding-left:5.03pt; vertical-align:middle;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:11pt;"><span
                                style="font-family:'Times New Roman';">Đọc</span></p>
                    </td>
                    <td
                        style="width:34.3pt; border-style:solid; border-width:0.75pt; padding-right:5.03pt; padding-left:5.03pt; vertical-align:middle;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:11pt;"><span
                                style="font-family:'Times New Roman';">Viết</span></p>
                    </td>
                    <td
                        style="width:68.35pt; border-style:solid; border-width:0.75pt; padding-right:5.03pt; padding-left:5.03pt; vertical-align:middle;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:11pt;"><span
                                style="font-family:'Times New Roman';">B&igrave;nh qu&acirc;n</span></p>
                    </td>
                    <td
                        style="width:27.2pt; border-style:solid; border-width:0.75pt; padding-right:5.03pt; padding-left:5.03pt; vertical-align:middle;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:11pt;"><span
                                style="font-family:'Times New Roman';">Có</span></p>
                    </td>
                    <td
                        style="width:27.2pt; border-style:solid; border-width:0.75pt; padding-right:5.03pt; padding-left:5.03pt; vertical-align:middle;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:11pt;"><span
                                style="font-family:'Times New Roman';">Vắng</span></p>
                    </td>
                    <td
                        style="width:27.2pt; border-style:solid; border-width:0.75pt; padding-right:5.03pt; padding-left:5.03pt; vertical-align:middle;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:11pt;"><span
                                style="font-family:'Times New Roman';">Muộn</span></p>
                    </td>
                    <td
                        style="width:27.2pt; border-style:solid; border-width:0.75pt; padding-right:5.03pt; padding-left:5.03pt; vertical-align:middle;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:11pt;"><span
                                style="font-family:'Times New Roman';">Có làm</span></p>
                    </td>
                    <td
                        style="width:27.2pt; border-style:solid; border-width:0.75pt; padding-right:5.03pt; padding-left:5.03pt; vertical-align:middle;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:11pt;"><span
                                style="font-family:'Times New Roman';">Không làm</span></p>
                    </td>
                    <td
                        style="width:27.2pt; border-style:solid; border-width:0.75pt; padding-right:5.03pt; padding-left:5.03pt; vertical-align:middle;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:11pt;"><span
                                style="font-family:'Times New Roman';">Đã làm - không đầy đủ</span></p>
                    </td>
                </tr>
                @isset($list_class)
                @php
                $stt=0;
                @endphp
                    @foreach ($list_class as $key => $item)
                        @php
                            if (isset($item->class_id->json_params->teacher)) {
                                $teacher=App\Models\Admin::find($item->class_id->json_params->teacher) ;
                            }

                            $params_score['class_id'] = $item->class_id;
                            $params_score['user_id'] = $item->user_id;
                            
                            $score = App\Models\Score::getsqlScore($params_score)->first();
                            $getsqlAttendance = App\Models\Attendance::getsqlAttendance($params_score)->get();

                            $attendant= $getsqlAttendance->filter(function ($val, $key) {
                                return $val->status == App\Consts::ATTENDANCE_STATUS['attendant'];
                            });

                            $absent= $getsqlAttendance->filter(function ($val, $key) {
                                return $val->status == App\Consts::ATTENDANCE_STATUS['absent'];
                            });

                            $late= $getsqlAttendance->filter(function ($val, $key) {
                                return $val->status == App\Consts::ATTENDANCE_STATUS['late'];
                            });

                            $is_homework_have= $getsqlAttendance->filter(function ($val, $key) {
                                return $val->is_homework == 0;
                            });

                            $is_homework_not_have = $getsqlAttendance->filter(function ($val, $key) {
                                return $val->is_homework == 1;
                            });

                            $is_homework_did_not_complete = $getsqlAttendance->filter(function ($val, $key) {
                                return $val->is_homework == 2;
                            });

                            $absent_has_reason = $absent->filter(function ($val, $key) {
                                return $val->json_params->value == 'there reason';
                            });
                            isset($absent_has_reason)?$has_reason = count($absent_has_reason):$has_reason = 0;

                            $absent_no_reason = $absent->filter(function ($val, $key) {
                                return $val->json_params->value == 'no reason';
                            });
                            isset($absent_no_reason) ? $no_reason = count($absent_no_reason):$no_reason = 0;

                            $count_late = 0;
                            foreach ($late as $value) {
                                $count_late += $value->json_params->value;
                            }
                        @endphp
                        @isset($item->class)
                        @php
                        $stt++;
                        @endphp
                        <tr style="height:25.5pt;">
                            <td
                                style="width:20.95pt; border-top-style:solid; border-top-width:0.75pt; border-right-style:solid; border-right-width:0.75pt; border-bottom-style:solid; border-bottom-width:0.75pt; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top;">
                                <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:11pt;"><span
                                        style="font-family:'Times New Roman';">{{ $stt }}</span></p>
                            </td>
                            <td
                                style="width:52.8pt; border-style:solid; border-width:0.75pt; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top;">
                                <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:11pt;"><span
                                        style="font-family:'Times New Roman';">{{ $item->class->name ?? '' }}</span></p>
                            </td>
                            <td
                                style="width:59.55pt; border-style:solid; border-width:0.75pt; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top;">
                                <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:11pt;"><span
                                        style="font-family:'Times New Roman';">{{ isset($teacher) ?$teacher->name:""}}</span></p>
                            </td>
                            <td
                                style="width:29.45pt; border-style:solid; border-width:0.75pt; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top;">
                                <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:11pt;"><span
                                        style="font-family:'Times New Roman';">{{ $item->class->level->name ?? '' }}</span></p>
                            </td>
                            <td
                                style="width:88.25pt; border-style:solid; border-width:0.75pt; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top;">
                                <p style="margin-top:0pt; margin-bottom:0pt; font-size:11pt;"><span
                                        style="font-family:'Times New Roman';">{{ $item->class->syllabus->name ?? '' }}</span></p>
                            </td>
                            <td
                                style="width:23.85pt; border-style:solid; border-width:0.75pt; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top;">
                                <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:11pt;"><span
                                        style="font-family:'Times New Roman';">{{ isset($score) && $score->score_listen != '' ? $score->score_listen : '_' }}</span></p>
                            </td>
                            <td
                                style="width:27.2pt; border-style:solid; border-width:0.75pt; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top;">
                                <p style="margin-top:0pt; margin-bottom:0pt; font-size:11pt;"><span
                                        style="font-family:'Times New Roman';">{{ isset($score) && $score->score_speak != '' ? $score->score_speak : '_' }}</span></p>
                            </td>
                            <td
                                style="width:30.65pt; border-style:solid; border-width:0.75pt; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top;">
                                <p style="margin-top:0pt; margin-bottom:0pt; font-size:11pt;"><span
                                        style="font-family:'Times New Roman';">{{ isset($score) && $score->score_read != '' ? $score->score_read : '_' }}</span></p>
                            </td>
                            <td
                                style="width:27.2pt; border-style:solid; border-width:0.75pt; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top;">
                                <p style="margin-top:0pt; margin-bottom:0pt; font-size:11pt;"><span
                                        style="font-family:'Times New Roman';">{{ isset($score) && $score->score_write != '' ? $score->score_write : '_' }}</span></p>
                            </td>
                            <td
                                style="width:27.2pt; border-style:solid; border-width:0.75pt; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top;">
                                <p style="margin-top:0pt; margin-bottom:0pt; font-size:11pt;"><span
                                        style="font-family:'Times New Roman';">{{ isset($score->json_params->score_average) && $score->json_params->score_average != '' ? $score->json_params->score_average : '_' }}</span></p>
                            </td>
                            <td
                                style="width:27.2pt; border-style:solid; border-width:0.75pt; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top;">
                                <p style="margin-top:0pt; margin-bottom:0pt; font-size:11pt;"><span
                                        style="font-family:'Times New Roman';">{{ $attendant->count() }}</span></p>
                            </td>
                            <td
                                style="width:27.2pt; border-style:solid; border-width:0.75pt; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top;">
                                <p style="margin-top:0pt; margin-bottom:0pt; font-size:11pt;"><span
                                        style="font-family:'Times New Roman';">{{ $absent->count() }}
                                        @if ($absent->count() > 0)
                                            (Có phép: {{ $has_reason }}, Không phép:
                                            {{ $no_reason }})
                                        @endif</span></p>
                            </td>
                            <td
                                style="width:27.2pt; border-style:solid; border-width:0.75pt; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top;">
                                <p style="margin-top:0pt; margin-bottom:0pt; font-size:11pt;"><span
                                        style="font-family:'Times New Roman';">{{ $late->count() }} lần
                                        @if ($late->count() > 0)
                                            (Tổng thời gian đi muộn: {{ $count_late }} phút)
                                        @endif</span></p>
                            </td>
                            <td
                                style="width:27.2pt; border-style:solid; border-width:0.75pt; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top;">
                                <p style="margin-top:0pt; margin-bottom:0pt; font-size:11pt;"><span
                                        style="font-family:'Times New Roman';">@if($is_homework_have->count()>0){{ $is_homework_have->count() }} lần @endif</span></p>
                            </td>
                            <td
                                style="width:34.3pt; border-style:solid; border-width:0.75pt; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top;">
                                <p style="margin-top:0pt; margin-bottom:0pt; font-size:11pt;"><span
                                        style="font-family:'Times New Roman';">@if($is_homework_not_have->count()>0){{ $is_homework_not_have->count() }} lần @endif</span></p>
                            </td>
                            <td
                                style="width:68.35pt; border-style:solid; border-width:0.75pt; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top;">
                                <p style="margin-top:0pt; margin-bottom:0pt; font-size:11pt;"><span
                                        style="font-family:'Times New Roman';">@if($is_homework_did_not_complete->count()>0){{ $is_homework_did_not_complete->count() }} lần @endif</span></p>
                            </td>
                            <td
                                style="width:189.6pt; border-top-style:solid; border-top-width:0.75pt; border-left-style:solid; border-left-width:0.75pt; border-bottom-style:solid; border-bottom-width:0.75pt; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top;">
                                <p style="margin-top:0pt; margin-bottom:0pt; font-size:11pt;"><span
                                        style="font-family:'Times New Roman';">&nbsp;</span></p>
                            </td>
                        </tr>
                        @endisset
                    @endforeach
                @endisset
            </tbody>
        </table>
        
        <p
            style="margin-top:0pt; margin-left:18pt; margin-bottom:0pt; text-indent:18pt; line-height:130%; font-size:12pt;">
            <span style="font-family:'Times New Roman';">Trên đây là báo cáo tình hình học tập của học viên <strong>{{ $detail->name??"" }}</strong> từ ngày .../.../.... đến ngày .../.../.... Mọi thắc mắc liên quan, học viên, Quý phụ huynh, Ban tuyển sinh vui lòng liên hệ phòng Chăm sóc khách hàng theo hotline: 0962.981.230 để được giải đáp.</span></p>
        <p
            style="margin-top:0pt; margin-left:18pt; margin-bottom:0pt; text-indent:18pt; line-height:130%; font-size:12pt;">
            <span style="font-family:'Times New Roman';">Tr&acirc;n trọng,</span></p>
        <table cellspacing="0" cellpadding="0" style="margin-left:18pt; border-collapse:collapse;">
            <tbody>
                <tr style="height:44.05pt;">
                    <td style="width:348.95pt; padding-right:5.4pt; padding-left:5.4pt; vertical-align:top;">
                        <p style="margin-top:0pt; margin-bottom:0pt; font-size:12pt;"><span
                                style="font-family:'Times New Roman';">&nbsp;</span></p>
                    </td>
                    <td style="width:348.95pt; padding-right:5.4pt; padding-left:5.4pt; vertical-align:top;">
                        <p
                            style="margin-top:0pt; margin-bottom:0pt; text-align:center; line-height:130%; font-size:12pt;">
                            <strong><span style="font-family:'Times New Roman';">T/M C&ocirc;ng ty TNHH DWN Việt
                                    Nam</span></strong></p>
                        <p
                            style="margin-top:0pt; margin-bottom:0pt; text-align:center; line-height:130%; font-size:12pt;">
                            <strong><span style="font-family:'Times New Roman';">Trưởng ph&ograve;ng đ&agrave;o
                                    tạo</span></strong></p>
                        <p
                            style="margin-top:0pt; margin-bottom:0pt; text-align:center; line-height:130%; font-size:12pt;">
                            <span style="font-family:'Times New Roman';">(Đ&atilde; k&yacute;)</span></p>
                    </td>
                </tr>
            </tbody>
        </table>
        <p style="margin-top:14pt; margin-bottom:14pt; line-height:150%; font-size:12pt;"><span
                style="font-family:'Times New Roman';">&nbsp;</span></p>
    </div>
</body>

</html>
