@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Artists</div>

                <div class="card-body">
                    @if(count($artists) > 0)
                        @foreach ($artists as $artist)
                            
                            <a href="{{ url('/'.$artist->url) }}">
                                <img src="/img/avatar/{{ $artist->avatar }}" width="150px" height="150px">
                                <h5>{{ $artist->name }}</h5>
                            </a>
                        @endforeach
                    @else
                        <p>No artist available.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
