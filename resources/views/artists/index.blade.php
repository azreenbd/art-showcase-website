@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between px-1">
        <h1 class="align-self-end">Artists</h1>
        <form action="{{ route('artist.index') }}" method="GET" class="form-inline">
            <div class="form-group">
                <label for="sort" class="mr-2">Sort by</label>
                <select id="sort" name="sort" class="form-control form-control-sm" onchange="if(this.value != 0) { this.form.submit(); }">
                    <option value="newest" @if ( app('request')->input('sort') == 'newest' || app('request')->input('sort') == null) selected @endif>Newest</option>
                    <option value="oldest" @if ( app('request')->input('sort') == 'oldest') selected @endif>Oldest</option>
                    <option value="a-z" @if ( app('request')->input('sort') == 'a-z') selected @endif>A to Z</option>
                    <option value="z-a" @if ( app('request')->input('sort') == 'z-a') selected @endif>Z to A</option>
                </select>
            </div>
        </form>
    </div>

    <div class="d-flex flex-wrap">
        @if(count($artists) > 0)
            @foreach ($artists as $artist)
            <a href="{{ url('/'.$artist->url) }}" class="thumbnail-artist">
                @if ( array_first($artist->artworks) )
                    <img class="cover-image rounded border border-whitesmoke" src="/storage/img/artwork/{{ array_first($artist->artworks->sortByDesc('created_at'))->filename }}" alt="{{ array_first($artist->artworks->sortByDesc('created_at'))->title }}" ondragstart="return false;" onselectstart="return false;" oncontextmenu="return false;">
                @else
                    <div class="cover-image rounded border border-whitesmoke"></div>
                @endif

                <img class="profile-image border rounded-circle" src="/storage/img/avatar/{{ $artist->avatar }}" alt="{{ $artist->name }}" ondragstart="return false;" onselectstart="return false;" oncontextmenu="return false;">

                <div class="thumbnail-content">
                    <h5 class="name pb-2 text-truncate" title="{{ $artist->name }}">{{ $artist->name }}</h5>
                </div>
            </a>
            @endforeach
        @else
            <p>No artist available.</p>
        @endif
    </div>

    {{ $artists->links() }}
</div>
@endsection
