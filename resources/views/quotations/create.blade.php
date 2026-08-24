@extends('layouts.app')

@section('title', 'Create Quotation')

@section('content')
    <div class="page-header">
        <div>
            <div class="page-title">Create Quotation</div>
            <div class="page-sub">Prepare a price offer variant for an event</div>
        </div>
    </div>

    @include('quotations.form')
@endsection
