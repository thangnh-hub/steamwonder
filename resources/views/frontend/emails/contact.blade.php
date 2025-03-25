@extends('frontend.layouts.email')

@section('content')
    <h1>@lang('You received a new contact from the system')</h1>

    <p>@lang('Content contact'): </p>

    <p>
        <strong>@lang('Name')</strong>: {{ $contact->name ?? '' }}
    </p>
    <p>
        <strong>@lang('Email')</strong>: {{ $contact->email ?? '' }}
    </p>
    <p>
        <strong>@lang('Phone')</strong>: {{ $contact->phone ?? '' }}
    </p>
    <p>
        <strong>@lang('Content note')</strong>: {{ $contact->content ?? '' }}
    </p>
@endsection
