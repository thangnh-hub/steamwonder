@extends('admin.layouts.auth')
@push('style')
    <style>
        .more_answer {
            padding: 10px;
        }
        .progress, .progress>.progress-bar, .progress .progress-bar, .progress>.progress-bar .progress-bar {
            border-radius: 20px;
        }
        .question-list {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin: 0px 20;
        }

        .question-item {
            /* cursor: pointer; */
            width: 50px;
            height: 50px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 50%;
            font-weight: bold;
            color: #fff;
        }

        .current-question {
            background-color: #28a745; 
        }
        .completed-question {
            background-color: #00c0ef; 
        }
        .pending-question {
            background-color: #ccc; 
        }
        .d-flex-between{
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
    </style>
@endpush
@section('title')
    @lang($module_name)
@endsection

@section('content-header')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang($module_name)
        </h1>
    </section>
@endsection

@section('content')
    <!-- Main content -->
    <section class="content">
        <div style="width: 100%;background: #FFF;text-align: center;">
            <img src="{{ asset('/data/dwn.jpg') }}" alt="DWN" style="width: 25%;">
        </div>
        <div class="box">
            <div class="box-header text-center">
                <h3 class="box-title">@lang('WELCOME TO THE PERSONAL COMPETENCIES ASSESSMENT TEST')</h3>
            </div>
            <div class="box-body">
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
                
                <form role="form" action="{{ route('next_question') }}" method="POST" >
                    @csrf
                    <input type="hidden" name="test_id" value="{{ $row->id }}" >
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="box box-primary">
                                <!-- form start -->
                                <div class="">
                                    <div class="nav-tabs-custom">
                                        <ul class="nav nav-tabs">
                                            <li class="active" style="display:flex;padding: 10px;  width: 100%;">
                                                   <h4><strong>Câu hỏi {{ $position + 1 }}: </strong>
                                                    {{ $row->info_question_curent->question ?? '' }} </h4>
                                            </li>
                                        </ul>
                                        <div class="tab-content">
                                            <div class="tab-pane  active">
                                                @foreach ($row->info_question_curent->json_params->answer as $k => $answer)
                                                    <div class="d-flex-wap more_answer">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="answer_{{ $row->current_question }}_{{ $k }}">
                                                                    {{ $answer->value }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <input {{ (isset($currentAnswer) && in_array($k, $currentAnswer)) ? "checked": "" }} 
                                                            id="answer_{{ $row->current_question }}_{{ $k }}" 
                                                            type="checkbox" name="answer[{{ $row->current_question }}][]" 
                                                            value="{{ $k }}">
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                            </div>
                            @if ($position < $total_questions - 1)
                            <button type="submit" class="btn btn-info pull-right">
                                 @lang('Câu tiếp theo ') <i class="fa fa-arrow-right "></i>
                            </button>
                            @endif
                        </div>
                        <div class="col-lg-4">
                            <div class="question-list">
                                @foreach ($row->json_params->questions_with_answers as $id => $answer)
                                    <div class="question-item 
                                       {{ $id == $row->current_question ? 'current-question' : ($answer != null ? 'completed-question' : 'pending-question') }}"
                                    >
                                        <span>Câu {{ $loop->iteration }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <h3 class="text-center">Tiến độ hoàn thành: {{ $answeredCount }}/{{ $total_questions }}</h3>
        <div class="progress">
            <div 
                class="progress-bar" 
                role="progressbar" 
                style="width: {{ ($answeredCount / $total_questions) * 100 }}%;" 
                aria-valuenow="{{ $answeredCount }}" 
                aria-valuemin="0" 
                aria-valuemax="{{ $total_questions }}">
                {{ round(($answeredCount / $total_questions) * 100, 2) }}%
            </div>
        </div>
        @if($row->status=='is_exam')
        <div class="d-flex-between">
            <form role="form" action="{{ route('previous_question') }}" method="POST" >
                @csrf
                <input type="hidden" name="test_id" value="{{ $row->id }}" >
                @if ($position > 0)
                    <button type="submit" name="previous" class="btn btn-info">
                        <i class="fa fa-arrow-left "></i> @lang('Xem câu trước ') 
                    </button>
                @endif
            </form>
            <form id="resultForm" role="form" onsubmit="return confirm('@lang('Bạn có thực sự muốn hoàn thành bài test ? Sau khi hoàn thành không thể thay đổi')')" action="{{ route('result_test_teacher') }}" method="GET" >
                @csrf
                <input type="hidden" name="test_id" value="{{ $row->id }}" >
                <input type="hidden" id="selectedAnswers" name="selected_answers" value="{{ isset($currentAnswer) ? json_encode($currentAnswer) : ""}}">
                <button type="submit" class="btn btn-success pull-right">
                    <i class="fa fa-save"></i> @lang('Nộp bài') 
                </button>
            </form>
        </div>
        @endif
    </section>
@endsection
@section('script')
    <script>
        $(document).ready(function () {
            // Khi checkbox thay đổi
            $('input[type="checkbox"]').trigger('change');
            $('input[type="checkbox"]').change(function () {
                let selectedAnswers = [];
                // Duyệt qua tất cả các checkbox đã chọn
                $('input[type="checkbox"]:checked').each(function () {
                    let answerId = $(this).val(); // Lấy ID đáp án
                    if (!selectedAnswers) {
                        selectedAnswers = [];
                    }
                    selectedAnswers.push(answerId);
                });
                // Cập nhật input ẩn với JSON
                $('#selectedAnswers').val(JSON.stringify(selectedAnswers));
            });
        });
    </script>
@endsection
