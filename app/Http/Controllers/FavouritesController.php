<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;
use App\Artwork;

class FavouritesController extends Controller
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
            'artwork_id' => ['required', 'numeric'],
        ]);

        if($validate) {
            $artwork_id = $request['artwork_id'];
            $artwork_exist = Artwork::find($artwork_id)->exists();

            if($artwork_exist) {
                $user = Auth::user();
                $artwork_id = Artwork::find($artwork_id);

                // Write to db
                $user->favourites()->attach($artwork_id);

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
        $artwork = Artwork::find($id);

        if($artwork) {
            $user = Auth::user();

            // remove from db
            $user->favourites()->detach($artwork->id);

            return redirect()->back();
        }
        else {
            abort(404);
        }
    }
}
