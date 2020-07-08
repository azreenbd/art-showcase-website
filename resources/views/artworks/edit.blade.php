@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
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

            <figure align="center" class="bg-dark text-light rounded p-3">
                <img src="/storage/img/artwork/{{ $artwork->filename }}" class="img-fluid" style="max-height: 25vh;">
                <figcaption>{{ $artwork->filename }}</figcaption>
            </figure>

            <form method="POST" action="{{ route('art.update', $artwork->id) }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="title" class="form-label h5">Title <span class="text-danger">*</span></label>

                    <input id="title" type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title', $artwork->title) }}" autocomplete="title" autofocus>

                    @error('title')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description" class="form-label h5">{{ __('Description') }}</label>

                    <textarea id="description" class="form-control @error('description') is-invalid @enderror" name="description">{{ old('description', $artwork->description) }}</textarea>

                    @error('description')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <div class="text-danger">* Required</div>
                </div>
                
                <div class="form-group text-center">
                    <button type="submit" class="btn btn-primary">
                        Save
                    </button>
                </div>
            </form>

            <hr>

            <!-- Delete comment -->
            <!-- Button trigger modal -->
            <div class="text-center">
            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteArtworkModal">
                Delete
            </button>
            </div>
            
            <!-- Modal -->
            <div class="modal fade" id="deleteArtworkModal" tabindex="-1" role="dialog" aria-labelledby="deleteArtworkModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="deleteArtworkModalLabel">Delete artwork?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                        Delete artwork?
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <form id="delete-profile" action="{{ route('art.destroy', $artwork->id )}}" method="POST">
                        @method('DELETE')
                        @csrf
                        <input class="btn btn-danger" type="submit" value="Delete">
                    </form>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
