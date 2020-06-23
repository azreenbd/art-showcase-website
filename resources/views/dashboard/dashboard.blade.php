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
                    
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
