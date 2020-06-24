@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ $artist->name }} <small>({{ $artist->fullname }})</small></div>
                
                <div class="card-body">
                    <img src="/img/avatar/{{ $artist->avatar }}">
                    <p>{{ $artist->about }}</p>

                    <h3>Artwork</h3>
                    @if(Auth::user()->id == $artist->user_id)
                        <a class="btn btn-light" href="{{ route('art.create') }}" role="button">Upload</a>
                    @endif

                    @forelse ($artworks as $artwork)
                        <a href="{{ url('/art/'.$artwork->id) }}"><img src="/img/artwork/{{ $artwork->filename }}"></a>
                    @empty
                        <p>No artwork available.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
