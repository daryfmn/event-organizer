@extends('layouts.app')

@section('title', 'Add Vendor')

@section('content')
    <div class="page-header">
        <div>
            <div class="page-title">Add Vendor</div>
            <div class="page-sub">Add a new supplier to your vendor list</div>
        </div>
    </div>

    @include('vendors.form')
@endsection
