<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Image;
use App\Artist;
use App\Artwork;
use App\User;

class ArtworksController extends Controller
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
        //
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
        $artist_exist = Auth::user()->artist->exists();
        if($artist_exist) {
            return view('artworks.upload');
        }
        else {
            return redirect('/dashboard');
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
            'artwork' => ['required', 'image'],
            'title' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);
        
        // If data is valid
        if($validate) {
            $user_id = Auth::user()->id;

            //return dd(Auth::user()->artist);

            // Check if user already have an artist profile
            $artist_exist = Auth::user()->artist->exists();
            if($artist_exist) {
                // Get artist data
                $artist = User::find($user_id)->artist;

                //return dd($artist);
                
                // Create new artwork
                $artwork = new Artwork;

                // Manipulate artwork image
                if($request->hasFile('artwork')) {
                    $avatar = $request->file('artwork');
                    $filename = $artist->id . '_' . time() . '.jpg';

                    $height = Image::make($avatar)->height();
                    $width = Image::make($avatar)->width();

                    // Convert and upload the image
                    if($height > $width) {
                        // Resize width
                        Image::make($avatar)->resize(1000, null, function ($constraint) {
                            $constraint->aspectRatio();
                            $constraint->upsize();
                        })->encode('jpg', 50)->save( public_path('/img/artwork/' . $filename) );
                    } else {
                        // Resize height
                        Image::make($avatar)->resize(null, 1000, function ($constraint) {
                            $constraint->aspectRatio();
                            $constraint->upsize();
                        })->encode('jpg', 50)->save( public_path('/img/artwork/' . $filename) );
                    }

                    $artwork->filename = $filename;
                }

                // Set request data to class(model)
                $artwork->title = $request['title'];
                $artwork->description = $request['description'];
                $artwork->artist_id = $artist->id;

                $artwork->save();

                return redirect('/art/'.$artwork->id);
            }
            else {
                return redirect('/dashboard');
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $artwork = Artwork::find($id);
        $favourites = $artwork->favourites; // User that favourite this artwork
        $isFavourite = false; // To check whether current logged in user favourite this artwork

        // If logged in
        if(Auth::check()) {
            foreach($favourites as $favourite) {
                if($favourite->id == Auth::user()->id) {
                    $isFavourite = true;
                }
            }
        }

        return view('artworks.show')->with('artwork', $artwork)->with('isFavourite', $isFavourite);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
