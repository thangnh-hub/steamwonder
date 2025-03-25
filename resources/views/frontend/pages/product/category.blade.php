{{-- Check và gọi template tương ứng --}}
{{-- @dd($page) --}}
@extends('frontend.layouts.default')

@section('content')
    @include('frontend.layouts.' . $page->json_params->template)
@endsection
