<?php

namespace App\Http\Controllers\API;

use App\Models\Type;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Location;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Rental;
use Illuminate\Support\Facades\Storage;

class FrontController extends Controller
{
    public function types()
    {
        return response()->json(Type::select(['id', 'name'])->get());
    }
    public function locations()
    {
        return response()->json(Location::select(['id', 'name'])->get());
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            "name" => ["required"],
            "email" => ["required", "email"],
            "phone" => ["required", "numeric", "min:10"],
            "address" => ["required"],
            "password" => ["required"],
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
        return response()->json(['message' => 'User Created  Sucessfully']);
    }


    public function showUser(User $user)
    {
        $user->load('vendor');

        return response()->json($user);
    }


    public function updateProfile(Request $request, User $user)
    {
        $data = $request->validate([
            "name" => ["required"],
            "email" => ["required", "email", "unique:users,email," . $user->id],
            "phone" => ["required", "numeric", "min:10"],
            "address" => ["required"],
            //"password" => ["required"],

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


    public function destroy(User $user)
    {
        try {
            $user->delete();
        } catch (\Exception $e) {
            return response()->json(['error' => 'You cannot delete user with related data!'], 401);
        }
        return response()->json(['message' => "User deleted Sucessfully"]);
    }

    function vehicles(Request $request)
    {
        $query = Vehicle::query();

        if (!empty($request->name)) {
            $query = $query->where('name', 'LIKE', '%' . $request->name . '%');
        }

        if (!empty($request->type_id)) {
            $query = $query->where('type_id', $request->type_id);
        }
        if (!empty($request->location_id)) {
            $query = $query->where('location_id', $request->location_id);
        }

        if (!empty($request->sort)) {
            $sort = $request->sort == "DESC" ? "DESC" : "ASC";
            $query = $query->orderBy('id', $sort);
        }

        $query = $query->where('is_available', true);

        $vehicles = $query->get();

        return response()->json($vehicles);
    }
    public function showVehicle(Vehicle $vehicle)
    {
        $vehicle->load('vendor');
        return response()->json($vehicle);
    }
    public function availableVehicles()
    {
        $vehicles = Vehicle::where('is_available', true)->get();
        return response()->json($vehicles);
    }

    public function checkVehicle(Request $request, Vehicle $vehicle)
    {
        $request->validate([
            'start_date' => ['required', 'date', 'after:' . now()],
            'end_date' => ['required', 'date', 'after:start_date'],
        ]);

        $start_date = $request->date('start_date');
        $end_date = $request->date('end_date');

        if (!$vehicle->is_available) {
            return response()->json([
                'status' => 'Vehicle is not available!',
                'is_available' => false,
            ]);
        }

        $rentals = Rental::where('vehicle_id', $vehicle->id)
            ->whereBetween('start_date', [$start_date, $end_date])
            ->orWhereBetween('end_date', [$start_date, $end_date])
            ->count();

        if ($rentals > 0) {
            return response()->json([
                'status' => 'Vehicle is booked for given time period.',
                'is_available' => false,
            ]);
        }

        return response()->json([
            'status' => 'Vehicle is available!',
            'is_available' => true,
        ]);
    }
}
