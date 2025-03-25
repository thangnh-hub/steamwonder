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
        <ol class="breadcrumb">
            <li>
                <a href="/">
                    <i class="fa fa-dashboard"></i> Home
                </a>
            </li>
            <li class="active">@lang($module_name)</li>
        </ol>
    </section>
@endsection

@section('content')
    <section class="content">
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
        {{-- @if (1 == 2) --}}
        <div class="row">
            <div class="col-lg-2 col-xs-6 hidden">
                <!-- small box -->
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3 data-count="{{ $admission }}" class="counter"></h3>
                        <p>Cán bộ tuyển sinh</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-user-plus"></i>
                    </div>
                </div>
            </div><!-- ./col -->
            <div class="col-lg-2 col-xs-6 hidden">
                <!-- small box -->
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3 data-count="{{ $teacher }}" class="counter"></h3>
                        <p>Giáo viên</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-cube"></i>
                    </div>
                </div>
            </div><!-- ./col -->
            <div class="col-lg-4 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3 data-count="{{ $trial_student ?? '' }}" class="counter"></h3>
                        <p>Học viên học thử</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-users"></i>
                    </div>
                </div>
            </div><!-- ./col -->
            <div class="col-lg-4 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3 data-count="{{ $student ?? '' }}" class="counter"></h3>
                        <p>Học viên chính thức</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-users"></i>
                    </div>
                </div>
            </div><!-- ./col -->
            <div class="col-lg-4 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3 data-count="{{ $student_liquidation_of_admission ?? '' }}" class="counter"></h3>
                        <p>Học viên đã thanh lý</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-users"></i>
                    </div>
                </div>
            </div><!-- ./col -->
        </div><!-- /.row -->


        <div class="row ">
            <div class="col-lg-6 col-xs-12">
                <div class="box box-info collapsed-box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Khóa học mới khai giảng</h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table no-margin">
                                <thead>
                                    <tr>
                                        <th>Tiêu đề</th>
                                        <th>Ngày khai giảng</th>
                                        <th>Tổng số học viên đăng ký</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($list_course as $course)
                                        <tr>
                                            <td>{{ $course->name ?? '' }}</td>
                                            <td>{{ isset($course->day_opening) && $course->day_opening!=""? date('d-m-Y', strtotime($course->day_opening)) : '' }}</td>
                                            <td>{{ $course->count_student ?? '' }} </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div><!-- /.table-responsive -->
                    </div><!-- /.box-body -->
                </div><!-- /.box -->

            </div><!-- ./col -->
            <div class="col-lg-6 col-xs-12">
                <div class="box box-info collapsed-box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Lớp mới khai giảng</h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table no-margin">
                                <thead>
                                    <tr>
                                        <th>Tiêu đề</th>
                                        <th>Giáo viên</th>
                                        <th>Sỹ số</th>
                                        <th>Khu vực</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($list_class as $row)
                                        @php
                                            $teacher = \App\Models\Teacher::where(
                                                'id',
                                                $row->json_params->teacher ?? 0,
                                            )->first();

                                            $quantity_student = \App\Models\UserClass::where('class_id', $row->id)
                                                ->get()
                                                ->count();
                                        @endphp
                                        <tr>
                                            <td>{{ $row->name ?? '' }}</td>
                                            <td>{{ $teacher->name ?? '' }}</td>
                                            <td> {{ $quantity_student }} </td>
                                            <td>{{ $row->area->name ?? '' }} </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div><!-- /.table-responsive -->
                    </div><!-- /.box-body -->
                </div><!-- /.box -->

            </div><!-- ./col -->
        </div><!-- /.row -->
        {{-- @endif --}}
    </section>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $('.counter').each(function() {
                var $this = $(this);
                var countTo = $this.attr('data-count');
                var duration = 1500;

                $({
                    countNum: $this.text()
                }).animate({
                    countNum: countTo
                }, {
                    duration: parseInt(duration),
                    easing: 'linear',
                    step: function() {
                        $this.text(Math.floor(this.countNum));
                    },
                    complete: function() {
                        $this.text(this.countNum);
                    }
                });
            });
            $('.col-lg-6.col-xs-12 .box.box-info .fa-minus').click();
        });
    </script>
@endsection
