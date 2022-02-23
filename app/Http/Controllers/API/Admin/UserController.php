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
        $users=User::with('vendor')->get();
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
        $data=$request->validate([
            "name"=>["required"],
            "email"=>["required"],
            "phone"=>["required"],
            "address"=>["required"],
            "password"=>["required"],
            "role"=>["required"],
            "image"=>["nullable",'image','mimes:png,jpeg,gif']
        ]);
        
            $ext=$request->file('image')->extension();
            $name=Str::random(20);
            $path=$name.".".$ext;

            $data['image']=$request->file('image')->storeAs('public/images',$path);
            
            
       
        User::create($data);
        return response()->json(['message' =>'User Created  Sucessfully']);
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
            "image"=>["nullable",'image','mimes:png,jpeg,gif']
        ]);
        
        /* $ext=$request->file('image')->extension();
        $name=Str::random(20);
        $path=$name.".".$ext;

        $data['image']= $request->file('image')->storeAs('public/images',$path);
        
            if($user->image){
            Storage::delete('public/'.$user->imgae);
            }
         */
        $user->update($data);

        return response()->json(['message' =>'User Updated Sucessfully']);
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
