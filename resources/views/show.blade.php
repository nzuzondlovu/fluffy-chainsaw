@extends('layouts.app')

@section('title', 'Results')

@section('content')
<div class="row">
    <div class="col mb-5">
        @if (isset($results))
        <ul class="list-group">
            @foreach ($results as $result)
            <li class="list-group-item">Cras justo odio</li>
            @endforeach
        </ul>
        @else
        <p class="class">
            Please use the form to get an exchange rate.
        </p>
        @endif
    </div>
</div>
@endsection