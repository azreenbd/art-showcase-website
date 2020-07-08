@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2 class="mb-4">New Artwork</h2>

            <form method="POST" action="{{ route('art.store') }}" enctype="multipart/form-data">
                @csrf
                
                <div class="form-group">
                    <input id="artwork" type="file" accept=".jpg, .jpeg, .png" class="form-control-file p-2 border rounded" name="artwork" autofocus>

                    @error('artwork')
                        <span class="invalid-feedback d-block col-md-6 offset-md-4" role="alert">
                            <strong>Please upload a valid image file.</strong>
                        </span>
                    @enderror
                </div>
                

                <div class="form-group">
                    <label for="title">{{ __('Title') }} <span class="text-danger">*</span></label>

                    <input id="title" type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title') }}" placeholder="Title">

                    @error('title')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description">{{ __('Description') }}</label>

                    <textarea id="description" class="form-control @error('description') is-invalid @enderror" name="description">{{ old('description') }}</textarea>

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
                        <i class="fas fa-arrow-up"></i> {{ __('Upload') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
