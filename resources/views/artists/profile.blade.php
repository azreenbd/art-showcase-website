@extends('layouts.app')

@section('content')
<div class="container">
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    {{ $artist->name }} <small>{{ $artist->fullname }}</small>
                    @auth
                        <!-- If user owned this profile -->
                        @if(Auth::user()->id == $artist->user_id)
                            <a class="btn btn-secondary" href="{{ route('artist.edit', $artist->url) }}" role="button">Edit Profile</a>
                        @endif
                    @endauth
                </div>
                
                <div class="card-body">
                    <img class="img-fluid mt-5" src="/storage/img/avatar/{{ $artist->avatar }}">

                    <h4>
                        @if($artist->website)
                            <a href="{{ $artist->website }}" title="{{ $artist->website }}" target="_blank"><i class="fas fa-globe"></i></a>
                        @endif
                        @if($artist->facebook)
                            <a href="{{ $artist->facebook }}" title="Facebook" target="_blank"><i class="fab fa-facebook"></i></a>
                        @endif
                        @if($artist->twitter)
                            <a href="{{ $artist->twitter }}" title="Twitter" target="_blank"><i class="fab fa-twitter"></i></a>
                        @endif
                        @if($artist->instagram)
                            <a href="{{ $artist->instagram }}" title="Instagram" target="_blank"><i class="fab fa-instagram"></i></a>
                        @endif
                        @if($artist->deviantart)
                            <a href="{{ $artist->deviantart }}" title="DeviantArt" target="_blank"><i class="fab fa-deviantart"></i></a>
                        @endif
                        @if($artist->artstation)
                            <a href="{{ $artist->artstation }}" title="ArtStation" target="_blank"><i class="fab fa-artstation"></i></a>
                        @endif
                        @if($artist->behance)
                            <a href="{{ $artist->behance }}" title="Behance" target="_blank"><i class="fab fa-behance"></i></a>
                        @endif
                    </h4>

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
                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#unfollowModal">
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
                        <a href="{{ url('/art/'.$artwork->id) }}"><img class="img-fluid" src="/storage/img/artwork/{{ $artwork->filename }}"></a>
                    @empty
                        <p>No artwork available.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
