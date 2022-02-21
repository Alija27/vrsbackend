<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users=User::all();
        return response()->json($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data=$request->validate([
            "name"=>["required"],
            "email"=>["required"],
            "phone"=>["required"],
            "address"=>["required"],
            "password"=>["required"],
            "role"=>["required"],
            "image"=>["nullable"]
        ]);
        User::create($data);
        return response()->json('User Created Sucessfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $data=$request->validate([
            "name"=>["required"],
            "email"=>["required"],
            "phone"=>["required"],
            "address"=>["required"],
            "password"=>["required"],
            "role"=>["required"],
            "image"=>["nullable"]
        ]);
        $user->update($data);
        return response()->json('User Updated Sucessfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json("User deleted Sucessfully");
    }
}
