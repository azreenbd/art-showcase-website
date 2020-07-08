@extends('layouts.app')

@section('content')
<div class="container">
    
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 px-4">
            @if($artist)
                <div class="d-flex mb-3 w-100 align-self-center">
                    <a href="{{ url('/'.$artist->url) }}">
                        <img src="{{ url('/storage/img/avatar/'.$artist->avatar) }}" title="{{ $artist->name }}" class="rounded-circle mr-2 border" style="object-fit: cover; width: 3rem; height:3rem;" ondragstart="return false;" onselectstart="return false;" oncontextmenu="return false;">
                    </a>
                    <div class="lead text-truncate align-self-center" title="{{ $artist->name }}">
                        <a href="{{ url('/'.$artist->url) }}">
                            {{ $artist->name }}
                        </a>
                    </div>
                </div>

                <div class="mt-4">
                    <span><h4 class="d-inline">Followers </h4><span class="text-muted">({{ count($artist->followers) }})</span></span>
                    <div class="d-flex flex-wrap">
                        @forelse ($artist->followers->take(15) as $follower)
                            @if ($follower->artist)
                                <a href="{{ url('/'.$follower->artist->url) }}">
                                    <img src="{{ url('/storage/img/avatar/'.$follower->artist->avatar) }}" title="{{ $follower->artist->name }}" class="rounded-circle m-1 border" style="object-fit: cover; width: 3rem; height:3rem;" ondragstart="return false;" onselectstart="return false;" oncontextmenu="return false;">
                                </a>
                            @else
                                <img src="{{ url('/storage/img/avatar/_default.jpg') }}" title="{{ $follower->username }}" class="rounded-circle m-1 border" style="object-fit: cover; width: 3rem; height:3rem;" ondragstart="return false;" onselectstart="return false;" oncontextmenu="return false;">
                            @endif                 
                        @empty
                            <p class="text-muted mt-2">You have no followers.</p>
                        @endforelse
    
                        @if ( count($artist->followers) > 0 )
                            <a href="#" class="btn btn-whitesmoke btn-block mt-2">More</a>
                        @endif
                    </div>
                </div>
            @else
                <div class="d-flex mb-3 w-100 align-self-center">
                    <img src="{{ url('/storage/img/avatar/_default.jpg') }}" title="{{ $user->username }}" class="rounded-circle mr-2 border" style="object-fit: cover; width: 3rem; height:3rem;" ondragstart="return false;" onselectstart="return false;" oncontextmenu="return false;">

                    <div class="lead text-truncate align-self-center" title="{{ $user->username }}">
                        {{ $user->username }}
                    </div>
                </div>

                <div class="text-center bg-whitesmoke rounded px-3 py-4">
                    <h5>Are you an artist?</h5>
                    <a class="btn btn-secondary" href="{{ route('artist.create') }}" role="button">Create Artist Profile</a>
                </div>
            @endif

            <div class="mt-4">
                <span><h4 class="d-inline">Following </h4><span class="text-muted">({{ count($user->follows) }})</span></span>
                <div class="d-flex flex-wrap">
                    @forelse ($user->follows->take(15) as $follow)
                        <a href="{{ url('/'.$follow->url) }}">
                            <img src="{{ url('/storage/img/avatar/'.$follow->avatar) }}" title="{{ $follow->name }}" class="rounded-circle m-1 border" style="object-fit: cover; width: 3rem; height:3rem;" ondragstart="return false;" onselectstart="return false;" oncontextmenu="return false;">
                        </a>                        
                    @empty
                        <p class="text-muted mt-2">You are not following any artist.</p>
                    @endforelse

                    @if ( count($user->follows) > 0 )
                        <a href="#" class="btn btn-whitesmoke btn-block mt-2">More</a>
                    @endif
                </div>
            </div>
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
                    <a href="?view=list" title="List view">
                        <i class="fas fa-th-list"></i>
                    </a>
                    <a href="?view=grid" title="Grid view">
                        <i class="fas fa-th-large"></i>
                    </a>
                </div>
            </div>
            
            <!-- Feeds Content -->
            <div class="d-flex flex-wrap">
            @forelse ($feeds as $feed)
                @if ( app('request')->input('view') == 'list' || app('request')->input('view') == null && Cookie::get('dashboard_view') == null || Cookie::get('dashboard_view') == 'list' && app('request')->input('view') == null )

                    {{ Cookie::queue(Cookie::make('dashboard_view', 'list', 525600)) }}

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

                @elseif ( app('request')->input('view') == 'grid' || Cookie::get('dashboard_view') == 'grid' && app('request')->input('view') == null)

                    {{ Cookie::queue(Cookie::make('dashboard_view', 'grid', 525600)) }}

                    <div class="thumbnail-art-title mb-3">
                        <div class="thumbnail-art rounded border">
                            <a href="{{ url('/art/'.$feed->id) }}">
                                <img src="/storage/img/artwork/{{ $feed->filename }}" class="rounded" ondragstart="return false;" onselectstart="return false;" oncontextmenu="return false;">
                            
                                <div class="overlay rounded">
                                    <div class="text">
                                        <div class="title">
                                            {{ $feed->title }}
                                        </div>
                                        <div class="icons pl-1">
                                            <span class="pr-1"><i class="fas fa-heart"></i> {{ count($feed->favourites) }}</span>
                                            <span class="pl-1"><i class="fas fa-comment"></i> {{ count($feed->comments) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="media mt-1 w-100">
                            <a href="{{ url('/'.$feed->artist->url) }}">
                                <img src="{{ url('/storage/img/avatar/'.$feed->artist->avatar) }}" title="{{ $feed->artist->name }}" class="rounded-circle mr-2 border" style="object-fit: cover; width:2rem; height:2rem;" ondragstart="return false;" onselectstart="return false;" oncontextmenu="return false;">
                            </a>
                            <div class="media-body overflow-hidden align-self-center">
                                <div>
                                    <div class="text-truncate" title="{{ $feed->artist->name }}">by <a href="{{ url('/'.$feed->artist->url) }}"><b>{{ $feed->artist->name }}</b></a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @empty
                <p class="text-muted">No new feeds.</p>
            @endforelse
            </div>

            {{ $feeds->links() }}
            
        </div>
    </div>
</div>
@endsection
