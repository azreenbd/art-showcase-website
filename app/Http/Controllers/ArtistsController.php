<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
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
    public function index(Request $request)
    {
        $artists_per_page = 32;

        if ($request['sort'] == "newest") {
            $artists = Artist::where('id', '>', 0)->orderBy('created_at', 'desc')->paginate($artists_per_page);
        }
        elseif ($request['sort'] == "oldest") {
            $artists = Artist::where('id', '>', 0)->orderBy('created_at', 'asc')->paginate($artists_per_page);
        }
        elseif ($request['sort'] == "a-z") {
            $artists = Artist::where('id', '>', 0)->orderBy('name', 'asc')->paginate($artists_per_page);
        }
        elseif ($request['sort'] == "z-a") {
            $artists = Artist::where('id', '>', 0)->orderBy('name', 'desc')->paginate($artists_per_page);
        }
        else {
            $artists = Artist::where('id', '>', 0)->orderBy('name', 'asc')->paginate($artists_per_page);
        }

        return view('artists.index')->with('artists', $artists->appends(Input::except('page')));
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

                    // Manipulate image
                    $img;
                    if($height > $width) {
                        // Resize width
                        $img = Image::make($avatar)->resize(400, null, function ($constraint) {
                            $constraint->aspectRatio();
                            $constraint->upsize();
                        });
                    } else {
                        // Resize height
                        $img = Image::make($avatar)->resize(null, 400, function ($constraint) {
                            $constraint->aspectRatio();
                            $constraint->upsize();
                        });
                    }
                    // Convert to jpg and crop to 400x400
                    $img->encode('jpg', 20);
                    if($height < 400 || $width < 400) {
                        if($height > $width) {
                            $img->crop($width, $width);
                        }
                        else {
                            $img->crop($height, $height);
                        }
                    }
                    else {
                        $img->crop(400, 400);
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

            $total_favourites = 0;

            foreach ($artworks as $artwork) {
                $total_favourites += count($artwork->favourites);
            }

            return view('artists.profile')->with('artist', $artist)->with('artworks', $artworks)->with('isFollowing', $isFollowing)->with('total_favourites', $total_favourites);

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

        if($artist && Auth::user()->artist && Auth::user()->artist->id == $artist->id) {
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
        // $id is artist custom url name
        $artist = Artist::where('url', $id)->first();
        $user = Auth::user();

        if($artist && $user->artist->id == $artist->id) {
            // Data validation
            $validate = $request->validate([
                'avatar' => ['nullable', 'image'],
                'name' => ['required', 'string', 'max:255'],
                'url' => ['required', 'string', 'max:255', Rule::unique('artists')->ignore($artist->id), 'alpha_dash'],
                'fullname' => ['nullable', 'string', 'max:255'],
                'about' => ['nullable', 'string', 'max:500'],
                'website' => ['nullable', 'string', 'max:255'],
                'facebook' => ['nullable', 'string', 'max:255'],
                'twitter' => ['nullable', 'string', 'max:255'],
                'instagram' => ['nullable', 'string', 'max:255'],
                'deviantart' => ['nullable', 'string', 'max:255'],
                'artstation' => ['nullable', 'string', 'max:255'],
                'behance' => ['nullable', 'string', 'max:255'],
            ]);
            
            // If data is valid
            if($validate) {
                // Manipulate avatar image
                if($request->hasFile('avatar')) {
                    $avatar = $request->file('avatar');
                    $filename = $artist->url . '-' . $artist->id . '_' . time() . '.jpg';

                    $height = Image::make($avatar)->height();
                    $width = Image::make($avatar)->width();

                    // Manipulate image
                    $img;
                    if($height > $width) {
                        // Resize width
                        $img = Image::make($avatar)->resize(400, null, function ($constraint) {
                            $constraint->aspectRatio();
                            $constraint->upsize();
                        });
                    } else {
                        // Resize height
                        $img = Image::make($avatar)->resize(null, 400, function ($constraint) {
                            $constraint->aspectRatio();
                            $constraint->upsize();
                        });
                    }
                    // Convert to jpg and crop to 400x400
                    $img->encode('jpg', 20);
                    if($height < 400 || $width < 400) {
                        if($height > $width) {
                            $img->crop($width, $width);
                        }
                        else {
                            $img->crop($height, $height);
                        }
                    }
                    else {
                        $img->crop(400, 400);
                    }
                    

                    // Save image to storage facade
                    Storage::put('public/img/avatar/'.$filename, $img->stream());
                    // Delete old profile picture
                    Storage::delete('public/img/avatar/'.$artist->avatar);

                    $artist->avatar = $filename;
                }

                // Set request data to class(model)
                $artist->name = $request['name'];
                $artist->fullname = $request['fullname'];
                $artist->url = $request['url'];
                $artist->about = $request['about'];
                $artist->website = $request['website'];
                $artist->facebook = $request['facebook'];
                $artist->twitter = $request['twitter'];
                $artist->instagram = $request['instagram'];
                $artist->deviantart = $request['deviantart'];
                $artist->artstation = $request['artstation'];
                $artist->behance = $request['behance'];

                $artist->save();

                $request->session()->flash('status', 'Profile updated successfully!');

                return redirect('/'.$artist->url);
            } 
            else {
                return redirect()->back();
            }
        }
        else {
            abort(404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // $id is custom url
        $artist = Artist::where('url', $id)->first();

        if(Auth::user()->artist && Auth::user()->artist->id == $artist->id) {
            $artist_name = $artist->name;
            $artist->delete();

            session()->flash('status', $artist_name.'\'s profile deleted successfully!');

            return redirect('/dashboard');
        } 
        else {
            session()->flash('error', 'Something when wrong, please try again!');

            return redirect()->back();
        }
    }
}
