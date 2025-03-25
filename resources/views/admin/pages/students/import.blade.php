@extends('admin.layouts.app')
@push('style')
    <style>
        .loading-notification {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            justify-content: center;
            align-items: center;
            text-align: center;
            font-size: 1.5rem;
            z-index: 9999;
        }
    </style>
@endpush
@section('title')
    @lang($module_name)
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang($module_name)
            <a class="btn btn-sm btn-warning pull-right" href="{{ route('students.create') }}"><i class="fa fa-plus"></i>
                @lang('Thêm mới học viên')</a>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div id="loading-notification" class="loading-notification">
            <p>@lang('Importing, please wait')...</p>
        </div>
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

        <div class="row">
            <div class="col-lg-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">@lang('Form')</h3>

                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->

                    @csrf
                    <div class="box-body">
                        <!-- Custom Tabs -->
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <li class="active">
                                    <a href="#tab_1" data-toggle="tab">
                                        <h5>@lang('Instructions for importing students') <span class="text-danger">*</span></h5>
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content">
                                <div class="tab-pane active" id="tab_1">
                                    <div class="d-flex-wap">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <a href="{{ url('data/student.xlsx') }}" download>
                                                    <button class="btn btn-sm btn-primary "><i class="fa fa-file-excel-o"
                                                            aria-hidden="true"></i>
                                                        @lang('Template import')</button> (@lang('Download and follow the instructions'))
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <h3>@lang('The excel file includes the following information fields'):</h3>
                                                <p class="text-danger">@lang('Lưu ý: Việc Import tại đây bằng với việc sửa thông tin học viên theo mã học viên đã có')</p>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <ul>
                                                            <li>1. @lang('Student code') (@lang('Student Code cannot be duplicated'))</li>
                                                            <li>2. @lang('Staff admission code')</li>
                                                            <li>3. @lang('Area')</li>
                                                            <li>4. Email (@lang('Emails cannot be duplicated'))</li>
                                                            <li>5. @lang('Phone')</li>
                                                            <li>6. @lang('Full name')</li>
                                                            <li>7. @lang('Address')</li>
                                                            <li>8. @lang('Birthday') (@lang('day/month/year format'))</li>
                                                            <li>9. @lang('Gender') (@lang('option:')
                                                                @lang('male') |
                                                                @lang('female') | @lang('other'))</li>
                                                            <li>10. @lang('Forms of training') (@lang('online | offline'))</li>
                                                            <li>11. @lang('Dad')</li>
                                                            <li>12. @lang('Dad phone')</li>
                                                        </ul>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <ul>
                                                            <li>13. @lang('Mami')</li>
                                                            <li>14. @lang('Mami phone')</li>
                                                            <li>15. @lang('CCCD')</li>
                                                            <li>16. @lang('Date range')</li>
                                                            <li>17. @lang('Issued by')</li>
                                                            <li>18. @lang('Contract type') (@lang('option:')
                                                                @foreach (\App\Consts::CONTRACT_TYPE as $key => $item)
                                                                    <label
                                                                        class="label label-primary">@lang($key)</label>
                                                                @endforeach
                                                                )
                                                            </li>
                                                            <li>19. @lang('Contract status') (@lang('option:')
                                                                @foreach (\App\Consts::CONTRACT_STATUS as $key => $item)
                                                                    <label
                                                                        class="label label-warning">@lang($key)</label>
                                                                @endforeach
                                                                )
                                                            </li>
                                                            <li>20. @lang('Contract performance status') (@lang('option:')
                                                                @foreach (\App\Consts::CONTRACT_PERFORMANCE_STATUS as $key => $item)
                                                                    <label
                                                                        class="label label-success">@lang($key)</label>
                                                                @endforeach
                                                                )
                                                            </li>
                                                            <li>21. @lang('Version') (@lang('option:')
                                                                @foreach (\App\Consts::VERSION_DEPT as $key => $item)
                                                                    <label
                                                                        class="label label-info">{{ $item }}</label>
                                                                @endforeach
                                                                )
                                                            </li>
                                                            <li>22. @lang('Ngành nghề') (Nếu có nhiều nhành nghề thì mỗi ngành nghề cách nhau bỏi dấu / )</li>
                                                            <li>23. @lang('Ghi chú') (CSKH)</li>
                                                            <li>24. @lang('Tình trạng học') (@lang('option:')
                                                                @foreach ($status_study as $val)
                                                                    <label
                                                                        class="label label-success">{{$val->name}}</label>
                                                                @endforeach
                                                                )</li>

                                                        </ul>
                                                    </div>
                                                </div>


                                                <span><strong>@lang('Note')<small class="text-red">*</small>:</strong>
                                                    @lang('Enter exactly as instructed')</span>
                                            </div>
                                            <div class="form-group">
                                                <span>@lang('When you have finished creating the data list, select the created excel file and click Import')</span>
                                            </div>
                                        </div>
                                        <div class="col-12 col-xs-12 col-sm-12 col-md-4">
                                            <div class="form-group">
                                                <h4>@lang('Select Staff admission code')</h4>
                                                <select name="" id="" class="select2 form-control">
                                                    <option>@lang('Please choose')</option>
                                                    @foreach ($rows as $item_admin)
                                                        <option value="{{ $item_admin->admin_code }}">
                                                            Mã cán bộ {{ $item_admin->name }}:
                                                            {{ $item_admin->admin_code }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12 col-xs-12 col-sm-12 col-md-4">
                                            <div class="form-group">
                                                <h4>@lang('Select Area')</h4>
                                                <select name="" id="" class="select2 form-control">
                                                    <option>@lang('Please choose')</option>
                                                    @foreach ($area as $item_area)
                                                        <option value="{{ $item_area->code }}">
                                                            Mã khu vực {{ $item_area->name }}: {{ $item_area->code }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12 col-xs-12 col-sm-12 col-md-4">
                                            <div class="form-group">
                                                <h4>@lang('Tra cứu ngành nghề')</h4>
                                                <select name="" id="" class="select2 form-control">
                                                    <option>@lang('Please choose')</option>
                                                    @foreach ($field as $item)
                                                        <option value="{{ $item->id }}">
                                                           {{ $item->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">@lang('Import Excel')</h4>
                                                </div>
                                                <form role="form" action="{{ route('students.store') }}" method="POST"
                                                    id="form_student" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="modal-body row">
                                                        <input type="hidden" name="import" value="true">
                                                        <input type="hidden" name="name" value="import">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label>@lang('File')</label>
                                                                <small class="text-red">*</small>
                                                                <input id="file" class="form-control" type="file"
                                                                    required name="file"
                                                                    placeholder="@lang('Select File')">
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="modal-footer" style="text-align: center">
                                                        <button type="button" class="btn btn-primary"
                                                            onclick="submitForm()"><i class="fa fa-file-excel-o"
                                                                aria-hidden="true"></i> @lang('Import')</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- /.tab-content -->
                        </div><!-- nav-tabs-custom -->

                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
        </div>
    </section>

@endsection

@section('script')
    <script>
        function submitForm() {
            if (document.getElementById('file').files.length == 0) {
                alert("Vui lòng chọn file trước khi thực hiện!");
                return;
            }
            document.getElementById("loading-notification").style.display = "flex";
            document.getElementById("form_student").submit();
        }
    </script>
@endsection
