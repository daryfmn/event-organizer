@extends('layouts.app')

@section('title', 'Create Event')

@section('content')
    <div class="page-header">
        <div>
            <div class="page-title">Create Event</div>
            <div class="page-sub">Add a new event to your schedule</div>
        </div>
    </div>

    @include('events.form')
@endsection
