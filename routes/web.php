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

// User Settings
Route::get('dashboard/settings', 'UsersController@edit')->name('user.edit');
Route::post('dashboard/settings', 'UsersController@update')->name('user.update');

/** Artist
* Route::resource('artist', 'ArtistsController');
*/
// Shorter artist url (id uses custom url instead)
Route::get('/artist', 'ArtistsController@index')->name('artist.index');
Route::get('/{id}', 'ArtistsController@show')->name('artist.show');

Route::post('/artist', 'ArtistsController@store')->name('artist.store');
Route::get('/artist/create', 'ArtistsController@create')->name('artist.create');

Route::get('/{id}/edit', 'ArtistsController@edit')->name('artist.edit');
Route::post('/{id}/edit', 'ArtistsController@update')->name('artist.update');

Route::delete('/{id}/delete', 'ArtistsController@destroy')->name('artist.destroy');

// Artwork
Route::resource('art', 'ArtworksController');

// Follow
Route::post('artist/follow', 'FollowsController@store')->name('follow.store');
Route::delete('artist/{id}/unfollow', 'FollowsController@destroy')->name('follow.destroy');

// Favourite
Route::post('art/favourite', 'FavouritesController@store')->name('favourite.store');
Route::delete('art/{id}/unfavourite', 'FavouritesController@destroy')->name('favourite.destroy');

// Comments
Route::post('art/{artwork_id}/comment', 'CommentsController@store')->name('comment.store');
Route::delete('art/{artwork_id}/comment/delete/{id}', 'CommentsController@destroy')->name('comment.destroy');
