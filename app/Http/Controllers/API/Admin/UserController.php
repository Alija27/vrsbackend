<?php

namespace App\Http\Controllers\API\Admin;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::with('vendor')->get();
        return response()->json($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $requsest
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            "name" => ["required"],
            "email" => ["required", "email"],
            "phone" => ["required", "numeric", "min:10"],
            "address" => ["required"],
            "password" => ["required"],
            "role" => ["required"],
            "image" => ["required", 'image', 'mimes:png,jpeg,gif'],

        ]);

        if ($request->file('image')) {
            $ext = $request->file('image')->extension();
            $name = Str::random(20);
            $path = $name . "." . $ext;
            $request->file('image')->storeAs('public/images', $path);
            $data['image'] = "images/" . $path;
        }




        User::create($data);
        return response()->json(['message' => 'User Created  Sucessfully']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $user->load('vendor');

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
        $data = $request->validate([
            "name" => ["required"],
            "email" => ["required", "email", "unique:users,email," . $user->id],
            "phone" => ["required", "numeric", "min:10"],
            "address" => ["required"],
            // "password" => ["required"],
            "role" => ["required"],
            "image" => ["nullable", 'image', 'mimes:png,jpeg,gif']
        ]);


        if ($request->hasFile('image')) {
            $ext = $request->file('image')->extension();
            $name = Str::random(20);
            $path = $name . "." . $ext;
            $request->file('image')->storeAs('public/images', $path);
            $data['image'] = "images/" . $path;

            if ($user->image) {
                Storage::delete('public/' . $user->image);
            }
        }

        $user->update($data);

        return response()->json(['message' => 'User Updated Sucessfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        try {
            $user->delete();
        } catch (\Exception $e) {
            return response()->json(['error' => 'You cannot delete user with related data!'], 401);
        }
        return response()->json(['message' => "User deleted Sucessfully"]);
    }
}
