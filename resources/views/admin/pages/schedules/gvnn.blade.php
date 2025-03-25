@extends('admin.layouts.app')


@section('title')
    @lang($module_name)
@endsection

@section('style')
    <style>

    </style>
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang($module_name)
        </h1>
    </section>

    <!-- Main content -->
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
        <form role="form" action="{{ route('gvnn.createdschedules') }}" method="POST">
            @csrf
            {{-- @method('PUT') --}}
            <div class="row">

                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="form-group">
                        <label>
                            @lang('Lớp')
                            <small class="text-red">*</small>
                        </label>
                        <select name="class_id" id="class_id" class="form-control select2" style="width: 100%" required>
                            <option value="">@lang('Please select')</option>
                            @foreach ($class as $item)
                                <option value="{{ $item->id }}">
                                    {{ $item->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">

                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->

                        <div class="box-body">
                            <div class="overflow-auto mt-15">
                                <table class="table  table-bordered table_leson">
                                    <thead>
                                        <tr>
                                            <th>@lang('Lesson')</th>
                                            <th>@lang('Status')</th>
                                            <th>@lang('Date-time')</th>
                                            <th>@lang('Period')</th>
                                            <th>@lang('Room')</th>
                                            <th>@lang('Teacher')</th>
                                            <th>@lang('Giáo viên phụ')</th>
                                            <th>@lang('Ghi chú')</th>
                                        </tr>
                                    </thead>
                                    <tbody class="lesson_body">
                                    </tbody>
                                </table>
                                <div class="box_btn" style="display:none">
                                    <button data-lesson="" class="form-group btn btn-primary mb-2 add_lesson"
                                        type="button"><i class="fa fa-plus"></i> @lang(' Thêm buổi học')</button>
                                    <button type="submit" class="btn btn-info btn-sm pull-right">
                                        <i class="fa fa-save"></i> @lang('Save')
                                    </button>
                                </div>
                            </div>
                        </div><!-- nav-tabs-custom -->
                    </div>
                </div>
            </div>
        </form>
    </section>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            var class_id = @json($class_id ?? '');
            if(class_id !=''){
                $('#class_id').val(class_id).trigger('change');
                getViewlesson(class_id, 'list')
            }

            $('#class_id').on('change', function() {
                var class_id = $(this).val();
                getViewlesson(class_id, 'list')
            })
            $('.add_lesson').click(function() {
                var class_id = $('#class_id').val();
                getViewlesson(class_id, 'item')
            })
            $(document).on('click', '.del_lesson', function(e) {
                e.preventDefault();
                var _this = $(this);
                var schedule_id = _this.attr("data-lesson-id");
                let url = "{{ route('ajax.lessonDestroy.gvnn') }}/";

                // Thêm hộp thoại xác nhận
                if (confirm("Bạn có chắc chắn muốn xóa buổi học này không?")) {
                    $.ajax({
                        type: "GET",
                        url: url,
                        data: {
                            schedule_id: schedule_id,
                        },
                        success: function(response) {
                            if (response.message == 'success') {
                                alert('Xóa thành công buổi học');
                                _this.parents('tr').fadeOut(800, function() {
                                    _this.parents('tr').remove();
                                });
                            } else {
                                alert('Không thể xóa buổi học!');
                            }
                        },
                        error: function(response) {
                            // Lấy lỗi
                            let errors = response.responseJSON.message;
                            alert(errors);
                        }
                    });
                }
            });

        });

        function getViewlesson(class_id, type) {
            var _count = Number($('.add_lesson').attr('data-lesson'));
            var url = "{{ route('gvnn.getschedules') }}";
            $.ajax({
                type: "GET",
                url: url,
                data: {
                    "class_id": class_id,
                    "type": type,
                    "count": _count,

                },
                success: function(response) {
                    if (response.data != 'error') {
                        var _html = response.data.html;
                        if (type == 'list') {
                            $('.lesson_body').html(_html);
                            $('.add_lesson').attr('data-lesson', response.data.count_lesson);
                        } else {
                            $('.lesson_body').append(_html);
                            $('.add_lesson').attr('data-lesson', _count + 1);
                        }
                        $('.box_btn').show();
                        $(".select2").select2();
                    } else {
                        alert(response.message);
                    }
                },
                error: function(response) {
                    var errors = response.responseJSON.message;
                    alert(errors);
                }
            });
        }

        function _delete_lesson(th) {
            $(th).parents('tr').fadeOut(800, function() {
                $(th).parents('tr').remove();
            });
        }
    </script>
@endsection
