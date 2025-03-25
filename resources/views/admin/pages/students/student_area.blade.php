@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection
@php
    if (Request::get('lang') == $languageDefault->lang_locale || Request::get('lang') == '') {
        $lang = $languageDefault->lang_locale;
    } else {
        $lang = Request::get('lang');
    }
@endphp
@push('style')
    <style>
        .box {
            border-top: 3px solid;
            border-bottom: 1px solid #CDCDCD;
            border-right: 1px solid #CDCDCD;
            border-left: 1px solid #CDCDCD;
            box-shadow: none;
        }

        .table-bordered>thead>tr>th,
        .table-bordered>thead>tr>td {
            border-bottom-width: 1px;
        }

        .table>thead>tr>th {
            font: normal 14px/28px "RobotoCondensed-Bold";
        }

        .table>thead>tr {
            background-color: #3c8dbc;
            color: #FFFFFF;
        }
    </style>
    <style>
        :root {
            --width: 36;
            --rounding: 4px;
            --accent: #696;
            --dark-grey: #ddd;
            --grey: #eee;
            --light-grey: #f8f8f8;
        }

        html .tree {
            font-weight: 300;
            font-size: clamp(18px, 100vw / var(--width), 15px);
            font-feature-settings: 'onum', 'pnum';
            line-height: 1.5;
            -webkit-text-size-adjust: none;
        }

        .tree {
            --spacing: 3rem;
            --radius: 10px;
            margin: 0px;
            padding: 0px;
        }

        .tree li {
            display: block;
            position: relative;
            padding-left: calc(2 * var(--spacing) - var(--radius) - 2px);
        }

        .tree ul {
            margin-left: calc(var(--radius) - var(--spacing));
            padding-left: 0;
        }

        .tree ul li {
            border-left: 2px solid #ddd;
        }

        .tree ul li:last-child {
            border-color: transparent;
        }

        .tree ul li::before {
            content: '';
            display: block;
            position: absolute;
            top: calc(var(--spacing) / -2);
            left: -2px;
            width: calc(var(--spacing) + 2px);
            height: calc(var(--spacing) + 1px);
            border: solid #ddd;
            border-width: 0 0 2px 2px;
        }

        .tree summary {
            display: block;
            cursor: pointer;
            padding-bottom: 10px;
        }

        .tree summary::marker,
        .tree summary::-webkit-details-marker {
            display: none;
        }

        .tree summary:focus {
            outline: none;
        }

        .tree summary:focus-visible {
            outline: 1px dotted #000;
        }

        .tree li::after,
        .tree summary::before {
            content: '';
            display: block;
            position: absolute;
            top: calc(var(--spacing) / 2 - var(--radius));
            left: calc(var(--spacing) - var(--radius) - 1px);
            width: calc(2 * var(--radius));
            height: calc(2 * var(--radius));
            border-radius: 50%;
            background: #ddd;
        }

        .tree summary::before {
            z-index: 1;
            background: #696 url({{ asset('themes/admin/expand-collapse.svg') }}) 0 0;
        }

        .tree details[open]>summary::before {
            background-position: calc(-2 * var(--radius)) 0;
        }

        .tree .table {
            margin-bottom: 10px;
        }
    </style>
@endpush
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
        <div class="box box-primary">
            <div class="box-body table-responsive">
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

                <ul class="tree">
                    @foreach ($list_area as $item_area)
                        <li>
                            <details {{ $loop->index == 0 ? 'open' : '' }}>
                                <summary>{{ $item_area->name ?? '' }} </summary>
                                <ul>
                                    @if (isset($list_class) && count($list_class) > 0)
                                        @php
                                            $class_area = $list_class->filter(function ($item, $key) use ($item_area) {
                                                return $item->area_id == $item_area->id;
                                            });
                                        @endphp
                                        @if ($class_area)
                                            @foreach ($class_area as $item_class)
                                                @php
                                                    $student_class = $list_student->filter(function ($item, $key) use ($item_class) {
                                                        return in_array($item_class->id, explode(',',$item->class_id));
                                                    });
                                                @endphp
                                                <li>
                                                    <details>
                                                        <summary>@lang('Lớp'): {{ $item_class->name ?? '' }} ({{$student_class->count()}} Học viên)
                                                        </summary>
                                                        <table class="table table-bordered">
                                                            <tbody>
                                                                <tr>
                                                                    <th style="width: 10px">#</th>
                                                                    <th>@lang('Student code')</th>
                                                                    <th>@lang('Name')</th>
                                                                    <th>@lang('Email')</th>
                                                                    {{-- <th>@lang('Class')</th> --}}
                                                                    <th>@lang('Status')</th>
                                                                </tr>
                                                                @foreach ($student_class as $row)
                                                                    <tr>
                                                                        <td>{{ ++$loop->index }}.</td>
                                                                        <td>
                                                                            <a class="btn btn-sm" data-toggle="tooltip"
                                                                                title="@lang('Show')"
                                                                                data-original-title="@lang('Show')"
                                                                                href="{{ route('students.edit', $row->id) }}">
                                                                                {{ $row->admin_code }}
                                                                            </a>
                                                                        </td>
                                                                        <td>
                                                                            {{ $row->name ?? '' }}
                                                                        </td>
                                                                        <td>
                                                                            {{ $row->email }}
                                                                        </td>

                                                                        {{-- <td>
                                                                            @if (isset($row->classs))
                                                                                @foreach ($row->classs as $i)
                                                                                    <p><a href="{{route('classs.edit', $i->id)}}" target="_blank">{{ $i->name }}</a></p>
                                                                                @endforeach
                                                                            @endif
                                                                        </td> --}}
                                                                        <td>
                                                                            @lang($row->StatusStudent->name ?? 'Chưa cập nhật')
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </details>
                                                </li>
                                            @endforeach
                                        @endif
                                    @endif
                                </ul>
                            </details>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </section>
@endsection
@section('script')
    <script></script>
@endsection
