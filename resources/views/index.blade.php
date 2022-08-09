@extends('layouts.app')

@section('title', 'Home')

@section('header')
<header class="bg-dark py-5">
    <div class="container px-4 px-lg-5 my-5">
        <div class="text-center text-white">
            <h1 class="display-4 fw-bolder">Query The Forex Exchange Rates</h1>
            <p class="lead fw-normal text-white-50 mb-0">Make sure that all fields are field and the dates are correct.
            </p>
        </div>
    </div>
</header>
@endsection

@section('content')
@if(Session::get('errors'))
<h5>There were errors:</h5>
@foreach($errors->all() as $message)
<div class="alert alert-danger d-flex align-items-center" role="alert">
    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:">
        <use xlink:href="#exclamation-triangle-fill" />
    </svg>
    <div>
        {{$message}}
    </div>
</div>
@endforeach
@endif
<div class="row">
    <div class="col mb-5">
        <form method="POST" action="/search">
            @csrf
            <div class="form-group">
                <label for="base_symbol">Base Currency</label>
                <select name="base_symbol" class="form-control" id="base_symbol" required>
                    @foreach ($symbols as $symbol)
                    <option value="{{ $symbol->code }}">{{ $symbol->code }} - {{ $symbol->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="start_date">Start Date</label>
                <input type="date" name="start_date" class="form-control" id="start_date" required>
            </div>
            <div class="form-group">
                <label for="end_date">End Date</label>
                <input type="date" name="end_date" class="form-control" id="end_date" required>
            </div>
            <button type="submit" class="btn btn-primary mt-4">Query Exchange Rate</button>
        </form>
    </div>
</div>
@endsection