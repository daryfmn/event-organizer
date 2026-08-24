@extends('layouts.app')

@section('title', 'Add Client')

@section('content')
    <div class="page-header">
        <div>
            <div class="page-title">Add Client</div>
            <div class="page-sub">Add a new company to your client list</div>
        </div>
    </div>

    @include('clients.form')
@endsection
