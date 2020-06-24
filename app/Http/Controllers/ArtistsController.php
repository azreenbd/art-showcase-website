<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Image;
use Auth;
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
        $artist_exist = Auth::user()->artist->exists();
        if(!$artist_exist) {
            return view('artists.create');
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
            $artist_exist = Auth::user()->artist->exists();
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
                    if($height > $width) {
                        // Resize width
                        Image::make($avatar)->resize(460, null, function ($constraint) {
                            $constraint->aspectRatio();
                            $constraint->upsize();
                        })->encode('jpg', 50)->save( public_path('/img/avatar/' . $filename) );
                    } else {
                        // Resize height
                        Image::make($avatar)->resize(null, 460, function ($constraint) {
                            $constraint->aspectRatio();
                            $constraint->upsize();
                        })->encode('jpg', 50)->save( public_path('/img/avatar/' . $filename) );
                    }

                    $artist->avatar = $filename;
                }

                // Set request data to class(model)
                $artist->name = $request['name'];
                $artist->fullname = $request['fullname'];
                $artist->url = $request['url'];
                $artist->about = $request['about'];
                $artist->user_id = $user_id;

                $artist->save();

                return redirect('/artist/'.$artist->url);
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
        $artworks = Artist::find($artist->id)->artworks;

        if($artist) {
            return view('artists.profile')->with('artist', $artist)->with('artworks', $artworks);
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
