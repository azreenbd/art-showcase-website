@extends('layouts.app')

@section('content')
<div class="container">
    
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 px-4 text-center">
            <img src="{{ url('/storage/img/avatar/'.$artist->avatar) }}" class="rounded-circle border" style="object-fit: cover; width: 95%; max-width: 50vw;" ondragstart="return false;" onselectstart="return false;" oncontextmenu="return false;">
            
            <div class="my-4">
                <span class="mx-2" title="Followers"><i class="fas fa-users"></i> {{ count($artist->followers) }}</span>
                <span class="mx-2" title="Likes"><i class="fas fa-heart"></i> {{ $total_favourites }}</span>
            </div>

            <!-- Follow button -->
            <div>
            @auth
                @if(!$isFollowing)
                    <!-- Follow an artist -->
                    <a class="btn btn-primary btn-lg text-light btn-block" onclick="event.preventDefault(); document.getElementById('follow-form').submit();">
                        Follow
                    </a>

                    <form id="follow-form" action="{{ route('follow.store') }}" method="POST" style="display: none;">
                        @csrf
                        <input type="hidden" id="artist_id" name="artist_id" value="{{ $artist->id }}">
                    </form>
                @else
                    <!-- Unfollow an artist -->
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-lg btn-block btn-danger" data-toggle="modal" data-target="#unfollowModal">
                        Following
                    </button>
                    
                    <!-- Modal -->
                    <div class="modal fade" id="unfollowModal" tabindex="-1" role="dialog" aria-labelledby="unfollowModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h5 class="modal-title" id="unfollowModalLabel">Unfollow {{ $artist->name }}?</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div>
                            <div class="modal-body">
                                Unfollow {{ $artist->name }}?
                            </div>
                            <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <form id="unfollow-form" action="{{ route('follow.destroy', $artist->url)}}" method="POST">
                                @method('DELETE')
                                @csrf
                                <input class="btn btn-danger" type="submit" value="Unfollow">
                            </form>
                            </div>
                        </div>
                        </div>
                    </div>
                @endif
            @else
                <a class="btn btn-primary btn-lg text-light btn-block" href="{{ route('login') }}">
                    Follow
                </a>
            @endauth
            </div>

            <!-- Social link -->
            <ul class="text-left p-0 mt-5" style="list-style: none; font-size: 1rem;">
                @if($artist->website)
                    <li><a href="{{ $artist->website }}" title="{{ $artist->website }}" target="_blank" class="text-decoration-none"><i class="fas fa-globe"></i> {{ $artist->website }}</a></li>
                @endif
                @if($artist->facebook)
                    <li><a href="{{ $artist->facebook }}" title="Facebook" target="_blank" class="text-decoration-none"><i class="fab fa-facebook"></i> Facebook</a></li>
                @endif
                @if($artist->twitter)
                    <li><a href="{{ $artist->twitter }}" title="Twitter" target="_blank" class="text-decoration-none"><i class="fab fa-twitter"></i> Twitter</a></li>
                @endif
                @if($artist->instagram)
                    <li><a href="{{ $artist->instagram }}" title="Instagram" target="_blank" class="text-decoration-none"><i class="fab fa-instagram"></i> Instagram</a></li>
                @endif
                @if($artist->deviantart)
                    <li><a href="{{ $artist->deviantart }}" title="DeviantArt" target="_blank" class="text-decoration-none"><i class="fab fa-deviantart"></i> DeviantArt</a></li>
                @endif
                @if($artist->artstation)
                    <li><a href="{{ $artist->artstation }}" title="ArtStation" target="_blank" class="text-decoration-none"><i class="fab fa-artstation"></i> ArtStation</a></li>
                @endif
                @if($artist->behance)
                    <li><a href="{{ $artist->behance }}" title="Behance" target="_blank" class="text-decoration-none"><i class="fab fa-behance"></i> Behance</a></li>
                @endif
            </ul>
        </div>

        <!-- Main content -->
        <div class="col-md-9 px-4">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            <div class="pt-4 pb-5">
                <div class="d-flex justify-content-between align-items-center">
                    <div style="line-height: 0">
                        <h1 class="m-0" style="font-size: 3rem;">{{ $artist->name }}</h1>
                        <p class="text-lightgrey m-0" style="font-size: 1rem;">{{ $artist->fullname }}</p>
                    </div>
                    <div>
                        @auth
                            <!-- If user owned this profile -->
                            @if(Auth::user()->id == $artist->user_id)
                                <a class="btn btn-secondary" href="{{ route('artist.edit', $artist->url) }}" role="button"><i class="fas fa-pen"></i> Edit Profile</a>
                            @endif
                        @endauth
                    </div>
                </div>
                <div class="media mt-5">
                    <i class="fas fa-quote-left text-lightgrey" style="font-size: 2.4rem"></i>
                    <div class="media-body px-2">
                        @if($artist->about)
                            {{ $artist->about }}
                        @else
                            No description given.
                        @endif
                        
                    </div>
                </div>
            </div>

            <h2 class="my-4">
                Artwork
                @auth
                    <!-- If user owned this profile -->
                    @if(Auth::user()->id == $artist->user_id)
                        <a class="btn btn-secondary" href="{{ route('art.create') }}" role="button"><i class="fas fa-arrow-up"></i> Upload</a>
                    @endif
                @endauth
            </h2>

            <div class="d-flex flex-wrap">
            @forelse ($artworks->sortByDesc('created_at') as $artwork)

                <div class="thumbnail-art rounded border">
                    <a href="{{ url('/art/'.$artwork->id) }}">
                        <img src="/storage/img/artwork/{{ $artwork->filename }}" class="rounded" ondragstart="return false;" onselectstart="return false;" oncontextmenu="return false;">
                    
                        <div class="overlay rounded">
                            <div class="text">
                                <div class="title pr-1">{{ $artwork->title }}</div>
                                <div class="icons pl-1">
                                    <span class="pr-1"><i class="fas fa-heart"></i> {{ count($artwork->favourites) }}</span>
                                    <span class="pl-1"><i class="fas fa-comment"></i> {{ count($artwork->comments) }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <p>No artwork available.</p>
            @endforelse
            </div>
        </div>

    </div>
</div>
@endsection
