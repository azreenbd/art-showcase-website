<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;

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
        $feeds = array();

        foreach($follows as $artist) {
            foreach($artist->artworks as $artwork) {
                array_push($feeds, $artwork);
            }
        }

        // Sort by newest artwork
        // change $feeds to collect($feeds) so you can use sortBy
        $feeds = collect($feeds)->sortByDesc('created_at');

        return view('dashboard.dashboard')->with('artist', $user->artist)->with('follows', $follows)->with('feeds', $feeds)->with('favourites', $favourites);
    }
}
