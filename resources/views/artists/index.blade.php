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
                            <h5><a href="{{ url('/'.$artist->url) }}">{{ $artist->name }}</a></h5>
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
