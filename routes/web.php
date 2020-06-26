<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('index');
});

Auth::routes();

// Dashboard
Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

// Artist
Route::resource('artist', 'ArtistsController');
Route::get('/{id}', 'ArtistsController@show'); // Shorter artist url

// Artwork
Route::resource('art', 'ArtworksController');

// Follow
Route::post('artist/follow', 'FollowsController@store')->name('follow.store');
Route::get('artist/{id}/unfollow', 'FollowsController@destroy');

// Favourite
Route::post('art/favourite', 'FavouritesController@store')->name('favourite.store');
Route::get('art/{id}/unfavourite', 'FavouritesController@destroy');
