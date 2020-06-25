@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ $artwork->title }}</div>

                <div class="card-body">
                    <p>{{ $artwork->description }}</p>
                    <img class="img-fluid" src="/img/artwork/{{ $artwork->filename }}">

                    <small>Uploaded on {{ $artwork->created_at->format('d M, Y') }}</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
