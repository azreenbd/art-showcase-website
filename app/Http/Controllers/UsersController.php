<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Auth;
use App\User;

class UsersController extends Controller
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $user = Auth::user();

        return view('dashboard.settings')->with('user', $user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            $validate = $request->validate([
                'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id), 'alpha_dash'],
                'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            ]);

            if ($validate) {
                $user->username = $request['username'];
                $user->email = $request['email'];

                $user->save();

                $request->session()->flash('success', 'Updated!');
            }
            else {
                $request->session()->flash('fail', 'Something when wrong! Try again.');
            }
        }
        else {
            $request->session()->flash('fail', 'Something when wrong! Try again.');
        }

        return redirect()->back();
    }
}
