@extends('layouts.app')

@section('title', 'Edit Client')

@section('content')
    <div class="page-header">
        <div>
            <div class="page-title">Edit Client</div>
            <div class="page-sub">{{ $client->company_name }}</div>
        </div>
    </div>

    @include('clients.form')
@endsection
