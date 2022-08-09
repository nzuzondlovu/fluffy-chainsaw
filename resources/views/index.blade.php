@extends('layouts.app')

@section('title', 'Home')

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