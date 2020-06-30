@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Artists</h1>

    <div class="card-group">
        @if(count($artists) > 0)
            @foreach ($artists as $artist)
                <a href="{{ url('/'.$artist->url) }}">
                    <div class="card m-1" style="width: 12rem;">
                        <div class="card-img-top" style="overflow:hidden; height: 12rem">
                            <img src="/storage/img/avatar/{{ $artist->avatar }}" alt="{{ $artist->name }}" style="object-fit: cover; width: 12rem; height:12rem;" ondragstart="return false;" onselectstart="return false;" oncontextmenu="return false;">
                        </div>
                        <div class="card-body p-0">
                            <h5 class="card-title pt-2 pl-3">{{ $artist->name }}</h5>
                        </div>
                    </div>
                </a>
            @endforeach
        @else
            <p>No artist available.</p>
        @endif
    </div>
</div>
@endsection
