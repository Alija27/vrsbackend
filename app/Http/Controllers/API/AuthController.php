<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function user()
    {
        $user = auth()->user();

        if ($user->role == "Vendor") {
            $user->load('vendor');
        }

        return response()->json($user);
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            "name" => ["required"],
            "email" => ["required", "email"],
            "phone" => ["required", "numeric", "min:10"],
            "address" => ["required"],
            "password" => ["required"],
            "role" => ["required"],
            "image" => ["required", 'image', 'mimes:png,jpeg,gif']
        ]);

        if ($request->file('image')) {
            $ext = $request->file('image')->extension();
            $name = Str::random(20);
            $path = $name . "." . $ext;
            $request->file('image')->storeAs('public/images', $path);
            $data['image'] = "images/" . $path;
        }




        User::create($data);
        return response()->json(['message' => 'Register  Sucessfully']);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return response()->json([
            'token' => $user->createToken(Str::random(20))->plainTextToken,
            'user' => $user,
        ]);
    }

    public function logout(Request $request)
    {
        if (!empty($request->token) && auth()->user()) {
            auth()->user()->tokens()->where('id', $request->token)->delete();
        }

        return response()->noContent();
    }
}
