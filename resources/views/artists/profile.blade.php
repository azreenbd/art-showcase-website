@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ $artist->name }}</div>
                
                <div class="card-body">
                    <p>{{ $artist->fullname }}</p>
                    <p>{{ $artist->about }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
