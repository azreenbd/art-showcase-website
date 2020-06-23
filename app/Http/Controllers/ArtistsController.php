<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Artist;
use App\User;

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
        return view('artists.create');
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
            'name' => ['required', 'string', 'max:255'],
            'url' => ['required', 'string', 'max:255', 'unique:artists', 'alpha_dash'],
            'fullname' => ['nullable', 'string', 'max:255'],
            'about' => ['nullable', 'string', 'max:500'],
        ]);
        
        // If data is valid
        if($validate) {
            $user_id = Auth::user()->id;

            // Check if user already have an artist profile
            $artist_exist = Artist::select('id')->where('id', $user_id)->exists();
            if(!$artist_exist) {
                // Create new artist
                $artist = new Artist;

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

        if($artist) {
            return view('artists.profile')->with('artist', $artist);
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
