<style>
    .smooth-scrol::-webkit-scrollbar {
        width: 10px;
    }
    .table-job th{
        text-align: start !important;
    }
    @media print {
        #job_hot {
            display: none; /* Ẩn nút khi in */
        }
    }
</style>
<section id="job_hot" class="content" style="margin-bottom: 0px; padding-bottom: 0px;min-height:0px;">
    <div class="row">
        <div class="col-lg-12 col-xs-12">
            <div class="box box-info collapsed-box">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        @lang('Danh sách tin đăng tuyển sinh mới')
                        <span class="label label-danger">HOT</span>
                    </h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-sm btn-info btn-flat pull-right">Xem tất cả</button>
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <div class="table-responsive smooth-scroll" style="height: 200px">
                        <table class="table no-margin table-job">
                            <thead>
                                <tr>
                                    <th>@lang('Title')</th>
                                    <th>@lang('Lịch phỏng vấn')</th>
                                    <th>@lang('Day_expried')</th>
                                    <th>@lang('Tổng CV ứng tuyển')</th>
                                    <th>@lang('Đạt/ Không đạt/ Vắng mặt/ Bị loại')</th>
                                    <th>@lang('Chi tiết')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cms_jobs as $row)
                                    @isset($user_action_header)
                                    @php
                                        $total = $user_action_header->filter(function ($item, $key) use ($row) {
                                            return $item->job_id == $row->id;
                                        });
                                        $pass=$user_action_header->filter(function ($item, $key) use ($row) {
                                            return $item->job_id == $row->id && $item->result_interview=='pass';
                                        });
                                        $nopass=$user_action_header->filter(function ($item, $key) use ($row) {
                                            return $item->job_id == $row->id && $item->result_interview=='nopass';
                                        });
                                        $absent=$user_action_header->filter(function ($item, $key) use ($row) {
                                            return $item->job_id == $row->id && $item->result_interview=='absent';
                                        });
                                        $cancel=$user_action_header->filter(function ($item, $key) use ($row) {
                                            return $item->job_id == $row->id && $item->result_interview=='cancel';
                                        });
                                    @endphp
                                    @endisset
                                        <tr class="valign-middle">
                                            <td>
                                                <a href="{{route('jobs.detail',$row->id)}}" target="_blank">
                                                    <strong style="font-size: 14px;">{{ $row->job_title ?? '' }}</strong>
                                                </a>
                                            </td>
                                            <td>
                                                {{ $row->time_interview!= ''?date('d-m-Y',strtotime($row->time_interview)):'' }}
                                            </td>
                                            <td>
                                               {{ $row->time_expired!= ''?date('d-m-Y',strtotime($row->time_expired)):'' }}
                                            </td>
                                            <td>
                                                {{ isset($total) ? count($total) : '0' }}
                                            </td>
                                            <td>
                                                {{ isset($pass) ? count($pass) : '0'}}/ {{ isset($nopass) ? count($nopass) : '0'}}/ {{ isset($absent) ? count($absent) : '0' }}/ {{ isset($cancel) ? count($cancel) : '0' }}
                                            </td>

                                            <td>
                                                <a target="_blank" href="{{ route('jobs.detail', $row->id) }}">Xem chi tiết</a>
                                            </td>
                                        </tr>
                                    @endforeach
                            </tbody>
                        </table>
                    </div><!-- /.table-responsive -->
                </div><!-- /.box-body -->

            </div><!-- /.box -->

        </div><!-- ./col -->
    </div>
</section>
