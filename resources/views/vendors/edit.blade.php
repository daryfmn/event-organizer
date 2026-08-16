@extends('layouts.app')

@section('title', 'Edit Vendor')

@section('content')
    <div class="page-header">
        <div>
            <div class="page-title">Edit Vendor</div>
            <div class="page-sub">{{ $vendor->name }}</div>
        </div>
    </div>

    @include('vendors.form')
@endsection
