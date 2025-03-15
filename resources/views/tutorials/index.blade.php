@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Tutorials de Reparació</h1>
        
        <form action="{{ route('tutorials.search') }}" method="GET">
            <input type="text" name="q" placeholder="Cercar tutorials...">
            <button type="submit">Cercar</button>
        </form>

        @foreach ($tutorials as $tutorial)
            <div class="card mb-3">
                <div class="card-body">
                    <h2>{{ $tutorial->title }}</h2>
                    <p>{{ $tutorial->description }}</p>
                    <a href="{{ route('tutorials.show', $tutorial) }}" class="btn btn-primary">Veure Tutorial</a>
                </div>
            </div>
        @endforeach

        {{ $tutorials->links() }}
    </div>
@endsection