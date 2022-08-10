@extends('layouts.app')

@section('title', 'Saved Results')

@section('header')
<header class="bg-dark py-5">
    <div class="container px-4 px-lg-5 my-5">
        <div class="text-center text-white">
            <h1 class="display-4 fw-bolder">Saves</h1>
            <p class="lead fw-normal text-white-50 mb-0">A list of all your saved exchange rates</p>
        </div>
    </div>
</header>
@endsection

@section('content')
<div class="row">
    <div class="col">
        @if (isset($results))
        <table>
            <thead>
                <th>Symbol</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th></th>
                <th></th>
            </thead>
            <tbody>
                @foreach ($results as $result)
                <tr>
                    <td>
                        {{ $result->symbol_code }}
                    </td>
                    <td>
                        {{ $result->start_date }}
                    </td>
                    <td>
                        {{ $result->end_date }}
                    </td>
                    <td>
                        <form action="/search" method="post">
                            @csrf
                            <input type="text" hidden name="start_date" value="{{ $result->start_date }}">
                            <input type="text" hidden name="end_date" value="{{ $result->end_date }}">
                            <input type="text" hidden name="base_symbol" value="{{ $result->symbol_code }}">
                            <button type="submit" class="btn btn-primary">View</button>
                        </form>
                    </td>
                    <td>
                        <a href="/delete/{{ $result->id }}" class="btn btn-danger">Delete</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p class="class">
            Please use the form to get an exchange rate.
        </p>
        @endif
    </div>
</div>
@endsection