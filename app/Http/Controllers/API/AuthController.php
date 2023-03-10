<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ForgotPassword;
use App\Http\Controllers\Controller;
use App\Notifications\ForgotPassword as NotificationsForgotPassword;
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
            "email" => ["required", "email", "unique:users,email"],
            "phone" => ["required", "numeric", "min:10", 'regex:/((98)|(97))([0-9]){8}/'],
            "address" => ["required"],
            "role" => ["required"],
            "image" => ["required",  "mimes:png,jpeg,gif"],
            "citizenship_number" => ["required"],
            "citizenship_image" => ["required",  "mimes:png,jpeg,gif"]
        ]);
        $data['password'] = bcrypt($request->password);

        if ($request->hasFile('image')) {
            $ext = $request->file('image')->extension();
            $name = Str::random(20);
            $path = $name . "." . $ext;
            $request->file('image')->storeAs('public/images', $path);
            $data['image'] = "images/" . $path;
        }
        if ($request->hasFile('citizenship_image')) {
            $ext = $request->file('citizenship_image')->extension();
            $name = Str::random(20);
            $path = $name . "." . $ext;
            $request->file('citizenship_image')->storeAs('public/images', $path);
            $data['citizenship_image'] = "images/" . $path;
        }



        User::create($data);
        return response()->json(['message' => 'Register Sucessfully']);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::with('vendor')->where('email', $request->email)->first();

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

    public function forgotpassword(Request $request)
    {
        $request->validate([
            'email' => ['required']
        ]);
        // yo line bata kun user tha hunxa
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => ["The provided email is not registered"],
            ]);
        } else {
            $forgotPassword = ForgotPassword::create(['user_id' => $user->id, 'otp' => str::random(6)]);
            $forgotDetails = [
                "otp" => $forgotPassword->otp,
                "user_name" => $user->name
            ];
            $user->notify(new NotificationsForgotPassword($forgotDetails));
        }

        return response()->json($user->id);
        /*  $user->notify(new ForgotPassword); */
    }

    public function OTPVerification(Request $request)
    {
        $request->validate([
            'otp' => ["required"]
        ]);
        // ani yaha chai tyo user ko token bata latest wala token ligeko
        $otp = ForgotPassword::where('otp', $request->otp)->where('user_id', $request->user_id)->orderBy('id', 'desc')->first();
        if (!$otp) {
            return response()->json(["error" => "OTP not Matched"]);
        }
        return response()->json(["message" => "OTP matched"]);
    }




    public function changePassword(Request $request, User $user)
    {
        $request->validate([
            "password" => ["required", "confirmed"],
        ]);
        $user->update(['password' => bcrypt($request->password)]);

        return response()->json([
            "message" => "Password Changed Successfully!"
        ]);
    }
}
