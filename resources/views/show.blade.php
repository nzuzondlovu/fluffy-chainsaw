@extends('layouts.app')

@section('title', 'Results')

@section('header')
<header class="bg-dark py-5">
    <div class="container px-4 px-lg-5 my-5">
        <div class="text-center text-white">
            <h1 class="display-4 fw-bolder">{{ $symbol->code }}</h1>
            <p class="lead fw-normal text-white-50 mb-0">{{ $symbol->name }}</p>
            <p class="lead fw-normal text-white-50 mb-0">{{ $dates[0] }} - {{ $dates[1] }}</p>
        </div>
    </div>
</header>
@endsection

@section('content')
<div class="row">
    <div class="col-md-6">
        @if (isset($results))
        <div class="accordion" id="accordionExample">
            @foreach ($results as $date => $result)
            <div class="accordion-item">
                <h2 class="accordion-header" id="heading{{ $date }}">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapse{{ $date }}" aria-expanded="false" aria-controls="collapse{{ $date }}">
                        {{ $date }}
                    </button>
                </h2>
                <div id="collapse{{ $date }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $date }}"
                    data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <ul class="list-group">
                            @foreach ($result as $rate)
                            <li class="list-group-item">1 {{ $rate->symbol->code }} - {{ $rate->rate_value }} {{
                                $rate->symbol_code }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <p class="class">
            Please use the form to get an exchange rate.
        </p>
        @endif
    </div>
    <div class="col">
        <h3>Min</h3>
        <p>1 {{ $symbol->code }} - {{ $min->rate_value }} {{
            $min->symbol_code }}</p>
    </div>
    <div class="col">
        <h3>Average</h3>
        <p>{{ $avg }}</p>
    </div>
    <div class="col">
        <h3>Max</h3>
        <p>1 {{ $symbol->code }} - {{ $max->rate_value }} {{
            $max->symbol_code }}</p>
    </div>
</div>
@endsection