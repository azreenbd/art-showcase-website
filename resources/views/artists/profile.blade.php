@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ $artist->name }} <small>({{ $artist->fullname }})</small></div>
                
                <div class="card-body">
                    <img class="img-fluid" src="/img/avatar/{{ $artist->avatar }}">
                    <br>
                    @auth
                        @if(!$isFollowing)
                            <!-- Follow an artist -->
                            <a class="btn btn-dark text-light" onclick="event.preventDefault(); document.getElementById('follow-form').submit();">
                                Follow
                            </a>

                            <form id="follow-form" action="{{ route('follow.store') }}" method="POST" style="display: none;">
                                @csrf
                                <input type="hidden" id="artist_id" name="artist_id" value="{{ $artist->id }}">
                            </form>
                        @else
                            <!-- Unfollow an artist -->
                            <a class="btn btn-danger text-light" href="{{ url('/artist/'.$artist->url.'/unfollow') }}">
                                Unfollow
                            </a>
                        @endif
                    @endauth
                    <p>{{ $artist->about }}</p>

                    <h3>Followers</h3>
                    <ul>
                    @forelse ($followers as $follower)
                        <li>{{ $follower->username }}</li>
                    @empty
                        <li>No followers.</li>
                    @endforelse
                    </ul>

                    <h3>Artwork</h3>
                    @auth
                        <!-- If user owned this profile -->
                        @if(Auth::user()->id == $artist->user_id)
                            <a class="btn btn-light" href="{{ route('art.create') }}" role="button">Upload</a>
                        @endif
                    @endauth

                    @forelse ($artworks as $artwork)
                        <a href="{{ url('/art/'.$artwork->id) }}"><img class="img-fluid" src="/img/artwork/{{ $artwork->filename }}"></a>
                    @empty
                        <p>No artwork available.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
