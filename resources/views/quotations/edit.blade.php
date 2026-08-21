@extends('layouts.app')

@section('title', 'Edit Quotation')

@section('content')
    <div class="page-header">
        <div>
            <div class="page-title">Edit Quotation</div>
            <div class="page-sub">{{ $quotation->quotation_number }}</div>
        </div>
    </div>

    @include('quotations.form')
@endsection
