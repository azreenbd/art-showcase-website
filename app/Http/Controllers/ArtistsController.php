<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Image;
use Auth;
use Storage;
use App\Artist;
use App\User;
use App\Artwork;

class ArtistsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $artists = Artist::all();

        return view('artists.index')->with('artists', $artists);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user_id = Auth::user()->id;

        // Check if user have an artist profile
        $artist_exist = Auth::user()->artist;
        if(!$artist_exist) {
            return view('artists.create');
        }
        else {
            return abort(404);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Data validation
        $validate = $request->validate([
            'avatar' => ['nullable', 'image'],
            'name' => ['required', 'string', 'max:255'],
            'url' => ['required', 'string', 'max:255', 'unique:artists', 'alpha_dash'],
            'fullname' => ['nullable', 'string', 'max:255'],
            'about' => ['nullable', 'string', 'max:500'],
        ]);
        
        // If data is valid
        if($validate) {
            $user_id = Auth::user()->id;

            // Check if user already have an artist profile
            $artist_exist = Auth::user()->artist;
            if(!$artist_exist) {
                // Create new artist
                $artist = new Artist;

                // Manipulate avatar image
                if($request->hasFile('avatar')) {
                    $avatar = $request->file('avatar');
                    $filename = Auth::user()->id . '_' . time() . '.jpg';

                    $height = Image::make($avatar)->height();
                    $width = Image::make($avatar)->width();

                    // Convert and upload the image
                    $img;

                    if($height > $width) {
                        // Resize width
                        $img = Image::make($avatar)->resize(460, null, function ($constraint) {
                            $constraint->aspectRatio();
                            $constraint->upsize();
                        })->encode('jpg', 50);
                    } else {
                        // Resize height
                        $img = Image::make($avatar)->resize(null, 460, function ($constraint) {
                            $constraint->aspectRatio();
                            $constraint->upsize();
                        })->encode('jpg', 50);
                    }

                    // Save image to storage facade
                    Storage::put('public/img/avatar/'.$filename, $img->stream());

                    $artist->avatar = $filename;
                }

                // Set request data to class(model)
                $artist->name = $request['name'];
                $artist->fullname = $request['fullname'];
                $artist->url = $request['url'];
                $artist->about = $request['about'];
                $artist->user_id = $user_id;

                $artist->save();

                return redirect('/'.$artist->url);
            } else {
                return redirect('/dashboard');
            }
        }
        return redirect()->back();
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // $id uses artist url instead of artist id
        $artist = Artist::where('url', $id)->first();

        if($artist) {
            $artworks = $artist->artworks;
            $followers = $artist->followers;
            $isFollowing = false;

            // If logged in
            if(Auth::check()) {
                foreach($followers as $follower) {
                    if($follower->id == Auth::user()->id) {
                        $isFollowing = true;
                    }
                }
            }

            return view('artists.profile')->with('artist', $artist)->with('artworks', $artworks)->with('followers', $followers)->with('isFollowing', $isFollowing);

        }
        else {
            abort(404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $artist = Artist::where('url', $id)->first();

        if($artist && Auth::user()->artist->id == $artist->id) {
            return view('artists.edit')->with('artist', $artist);
        }
        else {
            abort(404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
