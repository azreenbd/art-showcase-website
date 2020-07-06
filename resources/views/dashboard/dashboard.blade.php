@extends('layouts.app')

@section('content')
<div class="container">
    
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 px-4">
            @if($artist)
                <a class="btn btn-light" href="{{ url('/'.$artist->url) }}" role="button">Artist Profile</a>
            @else
                <h3>Are you an artist?</h3>
                <a class="btn btn-light" href="{{ route('artist.create') }}" role="button">Create Artist Profile</a>
            @endif

            <h3>Following</h3>
            <ul>
                @forelse ($follows as $follow)
                    <li><a href="{{ url('/'.$follow->url) }}">{{ $follow->name }}</a></li>
                @empty
                    <li>You are not following any artist.</li>
                @endforelse
            </ul>
        </div>

        <!-- Main content -->
        <div class="col-md-9 px-4">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <div class="d-flex justify-content-between align-items-center pb-3">
                <h2>Newest</h2>
                <div>
                    <a href="" title="List view">
                        <i class="fas fa-th-list"></i>
                    </a>
                    <a href="" title="Grid view">
                        <i class="fas fa-th-large"></i>
                    </a>
                </div>
            </div>

            <!-- Feeds Content -->
            @forelse ($feeds as $feed)
                <div class="container mb-4">
                    <div class="row">
                        <div class="media mb-3 w-100">
                            <a href="{{ url('/'.$feed->artist->url) }}">
                                <img src="{{ url('/storage/img/avatar/'.$feed->artist->avatar) }}" title="{{ $feed->artist->name }}" class="rounded-circle mr-2 border" style="object-fit: cover; width: 3rem; height:3rem;" ondragstart="return false;" onselectstart="return false;" oncontextmenu="return false;">
                            </a>
                            <div class="media-body overflow-hidden">
                                <div>
                                    <div class="lead text-truncate" title="{{ $feed->title }}">{{ $feed->title }}</div>
                                </div>
                                <span>by <a href="{{ url('/'.$feed->artist->url) }}"><b>{{ $feed->artist->name }}</b></a></span>
                            </div>
                        </div>
                    </div>

                    <!-- artwork -->
                    <a href="{{ url('/art/'.$feed->id) }}">
                        <div class="row rounded justify-content-center bg-whitesmoke border border-whitesmoke overflow-hidden">
                            <img class="mx-auto d-block" style="object-fit:cover; max-height:80vh; width: 100%;" src="/storage/img/artwork/{{ $feed->filename }}" ondragstart="return false;" onselectstart="return false;" oncontextmenu="return false;">
                        </div>
                    </a>

                    <!-- interactables icons -->
                    <div class="row py-2">
                        <iframe style="display:none;" name="favourite-iframe" id="favourite-iframe"></iframe>
                        <form id="favourite-form-{{ $feed->id }}" action="{{ route('favourite.store') }}" method="POST" style="display: none;" target="favourite-iframe" >
                            @csrf
                            <input type="hidden" id="artwork_id" name="artwork_id" value="{{ $feed->id }}">
                        </form>
                        <form id="unfavourite-form-{{ $feed->id }}" action="{{ route('favourite.destroy', $feed->id)}}" method="POST" style="display: none;" target="favourite-iframe">
                            @method('DELETE')
                            @csrf
                        </form>
                        <div class="d-flex justify-content-between">
                            <div>
                                <span>
                                    <!-- check if user already favourite this artwork -->
                                    @php ($isFavourited = false)
                                    @foreach($feed->favourites as $favourite)
                                        @if($favourite->id === Auth::user()->id)
                                            @php ($isFavourited = true)
                                        @endif
                                    @endforeach

                                    <span id="favourite-icon-{{ $feed->id }}">
                                    @if($isFavourited)
                                        <a title="Favourite" class="btn btn-link text-dark text-decoration-none py-0 px-1"  style="font-size: 1.125rem" onclick="event.preventDefault(); document.getElementById('unfavourite-form-{{ $feed->id }}').submit(); favouriteUpdate(false, {{ $feed->id }}, {{ count($feed->favourites) }});">
                                            <i class="fas fa-heart text-danger"></i> {{ count($feed->favourites) }}
                                        </a>
                                    @else
                                        <a title="Favourite" class="btn btn-link text-dark text-decoration-none py-0 px-1" style="font-size: 1.125rem" onclick="event.preventDefault(); document.getElementById('favourite-form-{{ $feed->id }}').submit(); favouriteUpdate(true, {{ $feed->id }}, {{ count($feed->favourites) }});">
                                            <i class="far fa-heart"></i> {{ count($feed->favourites) }}
                                        </a>
                                    @endif
                                    </span>  
                                    
                                    <a title="Comment" class="btn btn-link text-dark text-decoration-none py-0 px-1" href="{{ url('/art/'.$feed->id.'#comment') }}" style="font-size: 1.125rem">
                                        <i class="far fa-comment"></i> {{ count($feed->comments) }}
                                    </a>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- description -->
                    @if($feed->description)
                        <div class="row">
                            <p>{{ $feed->description }}</p>      
                        </div>
                    @endif
                    <div class="row">
                        <small class="text-muted">{{ $feed->created_at->format('d M, Y') }}</small>
                    </div>
                </div>
            @empty
                <p>No new feeds.</p>
            @endforelse

            {{ $feeds->links() }}
            
        </div>
    </div>
</div>
@endsection
