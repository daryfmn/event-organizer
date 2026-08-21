@extends('layouts.app')

@section('title', 'Edit Event')

@section('content')
    <div class="page-header">
        <div>
            <div class="page-title">Edit Event</div>
            <div class="page-sub">{{ $event->title }}</div>
        </div>
    </div>

    @include('events.form')
@endsection
