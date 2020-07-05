@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Edit Profile</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('artist.update', $artist->url) }}" enctype="multipart/form-data">
                        <h1>General</h1>
        
                        @csrf
                        @method('PUT')
        
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
                        
                        <div class="form-group">
                            <img class="img-fluid" src="/storage/img/avatar/{{ $artist->avatar }}">
                            <input id="avatar" type="file" accept=".jpg, .jpeg, .png" class=" form-control-file border rounded p-2" name="avatar">

                            @error('avatar')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>File upload only accept image file.</strong>
                                </span>
                            @enderror
                        </div>
                        

                        <div class="form-group">
                            <label for="name" class="form-label h5">{{ __('Artist name') }} <span class="text-danger">*</span></label>

                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $artist->name) }}" autocomplete="name" autofocus>

                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="url" class="form-label h5">{{ __('Custom URL') }} <span class="text-danger">*</span></label>

                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">{{ url('/') }}/</div>
                                </div>
                                <input id="url" type="text" class="form-control @error('url') is-invalid @enderror" name="url" value="{{ old('url', $artist->url) }}">

                                @error('url')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="fullname" class="form-label h5">{{ __('Real name') }}</label>

                            <input id="fullname" type="text" class="form-control @error('fullname') is-invalid @enderror" name="fullname" value="{{ old('fullname', $artist->fullname) }}" autocomplete="name">

                            @error('fullname')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="about" class="form-label h5">{{ __('About') }}</label>

                            <textarea id="about" class="form-control @error('about') is-invalid @enderror" name="about">{{ old('about', $artist->about) }}</textarea>

                            @error('about')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="text-danger">* Required</div>
                        </div>
        
                        <h2>Social Links</h2>

                        <div class="form-group">
                            <label for="website" class="form-label h5"><i class="fas fa-globe"></i> Website</label>
                            <input id="website" type="url" class="form-control @error('website') is-invalid @enderror" name="website" placeholder="https://www.yourwebsite.com/" value="{{ $artist->website }}">
                        
                            @error('website')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
        
                        <div class="form-group">
                            <label for="facebook" class="form-label h5"><i class="fab fa-facebook"></i> Facebook</label>
                            <input id="facebook" type="url" class="form-control @error('facebook') is-invalid @enderror" name="facebook" placeholder="https://www.facebook.com/" value="{{ $artist->facebook }}">
                            
                            @error('facebook')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
        
                        <div class="form-group">
                            <label for="twitter" class="form-label h5"><i class="fab fa-twitter"></i> Twitter</label>
                            <input id="twitter" type="url" class="form-control @error('twitter') is-invalid @enderror" name="twitter" placeholder="https://twitter.com/" value="{{ $artist->twitter }}">
                        
                            @error('twitter')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
        
                        <div class="form-group">
                            <label for="instagram" class="form-label h5"><i class="fab fa-instagram"></i> Instagram</label>
                            <input id="instagram" type="url" class="form-control @error('instagram') is-invalid @enderror" name="instagram" placeholder="https://www.instagram.com/" value="{{ $artist->instagram }}">
                        
                            @error('instagram')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="deviantart" class="form-label h5"><i class="fab fa-deviantart"></i> Deviantart</label>
                            <input id="deviantart" type="url" class="form-control @error('deviantart') is-invalid @enderror" name="deviantart" placeholder="https://www.deviantart.com/" value="{{ $artist->deviantart }}">
                        
                            @error('deviantart')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="artstation" class="form-label h5"><i class="fab fa-artstation"></i> Artstation</label>
                            <input id="artstation" type="url" class="form-control @error('artstation') is-invalid @enderror" name="artstation" placeholder="https://www.artstation.com/" value="{{ $artist->artstation }}">
                        
                            @error('artstation')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="behance" class="form-label h5"><i class="fab fa-behance"></i> Behance</label>
                            <input id="behance" type="url" class="form-control @error('behance') is-invalid @enderror" name="behance" placeholder="https://www.behance.net/" value="{{ $artist->behance }}">
                        
                            @error('behance')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                
                        <button type="submit" class="btn btn-primary">
                            Save
                        </button>
                    </form>

                    <!-- Delete comment -->
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-danger ml-3" data-toggle="modal" data-target="#deleteProfileModal">
                        Delete {{ $artist->name }} Profile
                    </button>
                    
                    <!-- Modal -->
                    <div class="modal fade" id="deleteProfileModal" tabindex="-1" role="dialog" aria-labelledby="deleteProfileModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h5 class="modal-title" id="deleteProfileModalLabel">Delete {{ $artist->name }}?</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div>
                            <div class="modal-body">
                                Deleting {{ $artist->name }} will permanently delete the artist profile and all artwork associated with the artist.
                            </div>
                            <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <form id="delete-profile" action="{{ route('artist.destroy', $artist->url )}}" method="POST">
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
    </div>
</div>
@endsection
