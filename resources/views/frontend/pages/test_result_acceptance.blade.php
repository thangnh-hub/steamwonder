@extends('admin.layouts.auth')
@push('style')
    <style>
        .more_answer {
            padding: 10px;
        }

        .small-box h3 {
            white-space: unset
        }
    </style>
@endpush

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="box">
            <div style="width: 100%;background: #FFF;text-align: center;">
                <img src="{{ asset('/data/dwn.jpg') }}" alt="DWN" style="width: 25%;">
            </div>
            <section class="content-header">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="small-box bg-aqua">
                                <div class="inner">
                                    <h3>@lang('Chúc mừng bạn đã hoàn thành bài thi')</h3>
                                    <p>{{$student->name??''}} - CCCD: {{$student->json_params->cccd??''}}</p>
                                    <p>@lang('Bạn đã đạt được'):
                                        {{ $point ?? 0 }} / 100
                                        điểm</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </section>

    </div>
@endsection
@section('script')
@endsection
