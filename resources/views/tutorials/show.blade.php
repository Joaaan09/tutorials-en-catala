@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ $tutorial->title }}</h1>
        
        @foreach($tutorial->steps as $step)
            <div class="card mb-3">
                <div class="card-body">
                    <h3>Pas {{ $step->order }}</h3>
                    <p>{{ $step->translated_instructions}}</p>
                    
                    @foreach($step->images as $image)
                        <img src="{{ $image->url }}" alt="Il·lustració del pas" class="img-fluid mb-2">
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
@endsection