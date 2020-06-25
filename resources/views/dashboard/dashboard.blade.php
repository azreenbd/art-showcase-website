@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

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

                    <h3>Feeds</h3>
                    @forelse ($feeds as $feed)
                        <a href="{{ url('/art/'.$feed->id) }}">
                            <div class="img-thumbnail my-2">
                                <h5>{{ $feed->title }} by {{ $feed->artist->name }}</h5>
                                <small>Uploaded on {{ $feed->created_at->format('d M, Y') }}</small>
                                <img class="img-fluid" src="/img/artwork/{{ $feed->filename }}">
                            </div>
                        </a>
                    @empty
                        
                    @endforelse
                    
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
