@extends('layouts.app')

@section('content')
<div class="container ">
    <ul class="list-unstyled">
        <li class="media mb-3">
            <a href="{{ url('/'.$artwork->artist->url) }}">
                <img src="{{ url('/storage/img/avatar/'.$artwork->artist->avatar) }}" title="{{ $artwork->artist->name }}" class="rounded-circle mr-3" style="object-fit: cover; width: 3rem; height:3rem;" ondragstart="return false;" onselectstart="return false;" oncontextmenu="return false;">
            </a>
            <div class="media-body text-truncate">
                <div>
                    <div class="lead">{{ $artwork->title }}</div>
                </div>
                <div class="d-flex justify-content-between">
                    <span>by <a href="{{ url('/'.$artwork->artist->url) }}"><b>{{ $artwork->artist->name }}</b></a></span>
                    <small class="text-muted">{{ $artwork->created_at->format('d M, Y') }}</small>
                </div>
            </div>
        </li>
    </ul>

    <img class="img-fluid mx-auto d-block" src="/storage/img/artwork/{{ $artwork->filename }}" style="max-height: 100vh">

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex d-flex">
                        <div>
                            <span>
                                @auth
                                    @if(!$isFavourite)
                                        <a title="Favourite" class="btn btn-link text-dark font-weight-bold py-0 px-1" onclick="event.preventDefault(); document.getElementById('favourite-form').submit();">
                                            <i class="far fa-heart"></i> {{ count($artwork->favourites) }}
                                        </a>

                                        <form id="favourite-form" action="{{ route('favourite.store') }}" method="POST" style="display: none;">
                                            @csrf
                                            <input type="hidden" id="artwork_id" name="artwork_id" value="{{ $artwork->id }}">
                                        </form>
                                    @else
                                        <a title="Favourite" class="btn btn-link font-weight-bold py-0 px-1" onclick="event.preventDefault(); document.getElementById('unfavourite-form').submit();">
                                            <i class="fas fa-heart text-danger"></i> {{ count($artwork->favourites) }}
                                        </a>

                                        <form id="unfavourite-form" action="{{ route('favourite.destroy', $artwork->id)}}" method="POST" style="display: none;">
                                            @method('DELETE')
                                            @csrf
                                        </form>
                                    @endif
                                @else
                                    <a title="Favourite" class="btn btn-link text-dark font-weight-bold py-0 px-1">
                                        <i class="far fa-heart"></i> {{ count($artwork->favourites) }}
                                    </a>
                                @endauth   
                                
                                <a title="Comment" class="btn btn-link text-dark font-weight-bold text-decoration-none py-0 px-1" href="#comment">
                                    <i class="far fa-comment"></i> {{ count($comments) }}
                                </a>
                            </span>
                            
                        </div>
                    </div>
                    
                    
                    <p>{{ $artwork->description }}</p>

                    

                    <h3 id="comment">Comments</h3>
                    @auth
                        <form method="POST" action="/art/{{ $artwork->id }}/comment" class="mb-3">
                            @csrf
                            <input type="hidden" id="artwork_id" name="artwork_id" value="{{ $artwork->id }}">
                            <div class="d-flex">
                                <div class="p-1">
                                    <!-- if user have an artist profile -->
                                    @if(Auth::user()->artist)
                                        <img src="{{ url('/storage/img/avatar/'.Auth::user()->artist->avatar) }}" class="rounded-circle" style="object-fit: cover; width: 2.5rem; height:2.5rem;" ondragstart="return false;" onselectstart="return false;" oncontextmenu="return false;">
                                    @else
                                        <img src="{{ url('/storage/img/avatar/_default.jpg') }}" class="rounded-circle" style="object-fit: cover; width: 2.5rem; height: 2.5rem;" ondragstart="return false;" onselectstart="return false;" oncontextmenu="return false;">
                                    @endif
                                </div>
                                <div class="flex-fill p-1">
                                    <label class="sr-only" for="comment">Comment</label>
                                    <input id="comment" type="text" class="form-control @error('comment') is-invalid @enderror" name="comment" value="{{ old('comment') }}" placeholder="Write a comment...">

                                    @error('comment')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="p-1">
                                    <button type="submit" class="btn btn-primary" title="Add comment">
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    @endauth

                    <!-- display comments -->
                    <ul class="list-unstyled">
                        @forelse ($comments->sortBy('created_at') as $comment)
                            <!-- if user have an artist profile -->
                            @if($comment->user->artist)
                                <li id="{{ $comment->id }}" class="media mb-3">
                                    <a href="{{ url('/'.$comment->user->artist->url) }}">
                                        <img src="{{ url('/storage/img/avatar/'.$comment->user->artist->avatar) }}" title="{{ $comment->user->artist->name }}" class="rounded-circle mr-3" style="object-fit: cover; width: 2.5rem; height:2.5rem;" ondragstart="return false;" onselectstart="return false;" oncontextmenu="return false;">
                                    </a>
                                    <div class="media-body d-flex flex-column">
                                        <a href="{{ url('/'.$comment->user->artist->url) }}"><b>{{ $comment->user->artist->name }}</b></a>
                                        <small class="text-muted">{{ $comment->created_at->format('d M, Y') }}</small>
                                        {{ $comment->comment }}
                                    </div>
                            @else
                                <li class="media mb-3">
                                    <img src="{{ url('/storage/img/avatar/_default.jpg') }}" title="{{ $comment->user->username }}" class="rounded-circle mr-3" style="object-fit: cover; width: 2.5rem; height:2.5rem;" ondragstart="return false;" onselectstart="return false;" oncontextmenu="return false;">

                                    <div class="media-body d-flex flex-column">
                                        <b>{{ $comment->user->username }}</b>
                                        <small class="text-muted">{{ $comment->created_at->format('d M, Y') }}</small>
                                        {{ $comment->comment }}
                                    </div>
                            @endif

                                    <!--Delete option if logged in user is the commentor-->
                                    @auth
                                        @if(Auth::user()->id == $comment->user_id)


                                            <!-- Delete comment -->
                                            <!-- Button trigger modal -->
                                            <button type="button" class="btn btn-link text-muted text-decoration-none ml-3" title="Delete" data-toggle="modal" data-target="#deleteCommentModal">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                            
                                            <!-- Modal -->
                                            <div class="modal fade" id="deleteCommentModal" tabindex="-1" role="dialog" aria-labelledby="deleteCommentModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteCommentModalLabel">Delete comment?</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Delete comment?
                                                    </div>
                                                    <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                    <form id="delete-comment" action="{{ route('comment.destroy', ['artwork_id'=>$artwork->id,'id'=>$comment->id] )}}" method="POST">
                                                        @method('DELETE')
                                                        @csrf
                                                        <input class="btn btn-danger" type="submit" value="Delete">
                                                    </form>
                                                    </div>
                                                </div>
                                                </div>
                                            </div>

                                        @endif
                                    @endauth
                                </li>
                        @empty
                            <p>No comment available.</p>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
