@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ $artwork->title }}</div>

                <div class="card-body">
                    <p>{{ $artwork->description }}</p>

                    @auth
                        @if(!$isFavourite)
                            <!-- Favourite an artwork -->
                            <a class="btn btn-dark text-light" onclick="event.preventDefault(); document.getElementById('favourite-form').submit();">
                                Favourite
                            </a>

                            <form id="favourite-form" action="{{ route('favourite.store') }}" method="POST" style="display: none;">
                                @csrf
                                <input type="hidden" id="artwork_id" name="artwork_id" value="{{ $artwork->id }}">
                            </form>
                        @else
                            <!-- Unfavourite an artwork -->
                            <a class="btn btn-danger text-light" href="{{ url('/art/'.$artwork->id.'/unfavourite') }}">
                                Unfavourite
                            </a>
                        @endif
                    @endauth

                    <img class="img-fluid" src="/img/artwork/{{ $artwork->filename }}">

                    <small>Uploaded on {{ $artwork->created_at->format('d M, Y') }}</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
