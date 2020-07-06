<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use App\User;
use App\Artwork;

class DashboardController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user_id = Auth::user()->id;
        $user = User::find($user_id);
        $follows = $user->follows;
        $favourites = $user->favourites;

        // Collect all artist id that the user follows
        $artists_id = array();
        foreach ($follows as $follow) {
            array_push($artists_id, $follow->id);
        }

        $feeds = Artwork::whereIn('artist_id', $artists_id)->orderBy('created_at','DESC')->paginate(20);
   
        return view('dashboard.dashboard')->with('artist', $user->artist)->with('follows', $follows)->with('feeds', $feeds)->with('favourites', $favourites);
    }
}
