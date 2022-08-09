@extends('layouts.app')

@section('title', 'Home')

@section('header')
<header class="bg-dark py-5">
    <div class="container px-4 px-lg-5 my-5">
        <div class="text-center text-white">
            <h1 class="display-4 fw-bolder">Shop in style</h1>
            <p class="lead fw-normal text-white-50 mb-0">With this shop hompeage template</p>
        </div>
    </div>
</header>
@endsection

@section('content')
<div class="row">
    <div class="col mb-5">
        <form method="POST" action="/search">
            @csrf
            <div class="form-group">
                <label for="base_symbol">Base Currency</label>
                <select name="base_symbol" class="form-control" id="base_symbol" required>
                    @foreach ($symbols as $symbol)
                    <option value="{{ $symbol->code }}">{{ $symbol->code }}</option>
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