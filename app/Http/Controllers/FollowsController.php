<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;
use App\Artist;

class FollowsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'artist_id' => ['required', 'numeric'],
        ]);

        if($validate) {
            $artist_id = $request['artist_id'];
            $artist_exist = Artist::find($artist_id)->exists();

            if($artist_exist) {
                $user = Auth::user();
                $artist = Artist::find($artist_id);

                // Write to db
                $user->follows()->attach($artist);

                return redirect()->back();
            } else {
                return redirect()->back();
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
        // $id uses artist url instead of artist id
        $artist = Artist::where('url', $id)->first();

        if($artist) {
            $user = Auth::user();

            // remove from db
            $user->follows()->detach($artist->id);

            return redirect()->back();
        }
        else {
            abort(404);
        }
    }
}
