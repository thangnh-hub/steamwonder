{{-- Check và gọi template tương ứng --}}
@extends('frontend.layouts.default')

@section('content')
    @include('frontend.layouts.' . $detail->json_params->template)
@endsection

