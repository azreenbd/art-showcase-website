@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2 class="mb-4">Create Profile</h2>

            <form method="POST" action="{{ route('artist.store') }}" enctype="multipart/form-data">
                @csrf
                
                <div class="form-group">
                    <img class="rounded" style="width: 15rem" src="/storage/img/avatar/_default.jpg">
                    <input id="avatar" type="file" accept=".jpg, .jpeg, .png" class="form-control-file rounded py-2" name="avatar">

                    @error('avatar')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>File upload only accept image file.</strong>
                        </span>
                    @enderror
                </div>
                

                <div class="form-group">
                    <label for="name">{{ __('Artist name') }} <span class="text-danger">*</span></label>

                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" autocomplete="name" placeholder="Name" autofocus>

                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="url">{{ __('Custom URL') }} <span class="text-danger">*</span></label>

                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">{{ url('/') }}/</div>
                        </div>
                        <input id="url" type="text" class="form-control @error('url') is-invalid @enderror" name="url" value="{{ old('url') }}">

                        @error('url')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="fullname">{{ __('Alternate name') }}</label>

                    <input id="fullname" type="text" class="form-control @error('fullname') is-invalid @enderror" name="fullname" value="{{ old('fullname') }}" autocomplete="name" placeholder="Real name, nickname, etc.">

                    @error('fullname')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="about">{{ __('About') }}</label>

                    <textarea id="about" class="form-control @error('about') is-invalid @enderror" name="about" value="{{ old('about') }}"></textarea>

                    @error('about')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <div class="text-danger">* Required</div>
                </div>

                <div class="form-group">
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Create') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
