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

                    <h3>Comments</h3>
                    @auth
                        <form method="POST" action="/art/{{ $artwork->id }}/comment">
                            @csrf
                            <input type="hidden" id="artwork_id" name="artwork_id" value="{{ $artwork->id }}">
                            <div class="form-row">
                                <div class="col-auto">
                                    <!-- if user have an artist profile -->
                                    @if(Auth::user()->artist)
                                        <img src="{{ url('/img/avatar/'.Auth::user()->artist->avatar) }}" class="rounded-circle" style="object-fit: cover; width: 2.5rem; height:2.5rem;" ondragstart="return false;" onselectstart="return false;" oncontextmenu="return false;">
                                    @else
                                        <img src="{{ url('/img/avatar/_default.jpg') }}" class="rounded-circle" style="object-fit: cover; width: 2.5rem; height: 2.5rem;" ondragstart="return false;" onselectstart="return false;" oncontextmenu="return false;">
                                    @endif
                                </div>
                                <div class="col-auto">
                                    <label class="sr-only" for="comment">Comment</label>
                                    <input id="comment" type="text" class="form-control @error('comment') is-invalid @enderror" name="comment" value="{{ old('comment') }}" placeholder="Write a comment...">

                                    @error('comment')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-primary">
                                        Cmt
                                    </button>
                                </div>
                            </div>
                        </form>
                    @endauth
                    <!-- display comments -->
                    @forelse ($comments as $comment)
                        <!-- if user have an artist profile -->
                        @if($comment->user->artist)
                            <a href="{{ url('/'.$comment->user->artist->url) }}">
                                <img src="{{ url('/img/avatar/'.$comment->user->artist->avatar) }}" title="{{ $comment->user->artist->name }}" class="rounded-circle" style="object-fit: cover; width: 2.5rem; height:2.5rem;" ondragstart="return false;" onselectstart="return false;" oncontextmenu="return false;">
                            </a>
                            <p>
                                <a href="{{ url('/'.$comment->user->artist->url) }}"><b>{{ $comment->user->artist->name }}</b></a>
                                <br>
                                {{ $comment->comment }}
                            </p>
                        @else
                            <img src="{{ url('/img/avatar/_default.jpg') }}" title="{{ $comment->user->username }}" class="rounded-circle" style="object-fit: cover; width: 2.5rem; height: 2.5rem;" ondragstart="return false;" onselectstart="return false;" oncontextmenu="return false;">
                            <p>
                                <b>{{ $comment->user->username }}</b>
                                <br>
                                {{ $comment->comment }}
                            </p>
                        @endif

                        <!--Delete option if logged in user is the commentor-->
                        @auth
                            @if(Auth::user()->id == $comment->user_id)
                                <a class="btn btn-danger text-light" href="{{ url('/art/'.$artwork->id.'/comment/delete/'.$comment->id) }}">
                                    Delete
                                </a>
                                <br>
                            @endif
                        @endauth

                    @empty
                        <p>No comment available.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
