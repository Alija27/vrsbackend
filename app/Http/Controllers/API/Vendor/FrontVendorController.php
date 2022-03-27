<?php

namespace App\Http\Controllers\API\Vendor;

use App\Models\Type;
use App\Models\Vehicle;
use App\Models\Location;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FrontVendorController extends Controller
{

    public function vehicleStatus(Request $request, Vehicle $vehicle)
    {
        $vehicle->update([
            'is_available' => $request->is_available == 1 ? true : false,
        ]);

        return response()->json([
            'is_available' => $request->is_available == 1 ? true : false,
        ]);
    }

    public function addVehicle(Request $request)
    {
        $data = $request->validate([
            "name" => ["required"],
            "vendor_id" => ["required", "exists:users,id"],
            "type_id" => ["required", "exists:types,id"],
            "model" => ["required"],
            "color" => ["required"],
            "total_seats" => ["required"],
            "rental_price" => ["required"],
            "description" => ["required"],
            "terms" => ["required"],
            "condition" => ["required"],
            "has_driver" => ["required"],
            "location_id" => ['required', 'exists:locations,id'],
            "image" => ["required", 'image', 'mimes:png,jpeg,gif']

        ]);

        if ($request->file('image')) {
            $request->validate(["image" => ['image', 'mimes:png,jpeg,gif']]);
            $ext = $request->file('image')->extension();
            $name = Str::random(20);
            $path = $name . "." . $ext;
            $request->file('image')->storeAs('public/images', $path);
            $data['image'] = "images/" . $path;
        }

        Vehicle::create($data);

        return response()->json(['message' => 'Vehicle Created sucessfully']);
    }

    public function updateVehicle(Request $request, Vehicle $vehicle)
    {
        $data = $request->validate([
            "name" => ["required"],
            "vendor_id" => ["required", "exists:users,id"],
            "type_id" => ["required", "exists:types,id"],
            "model" => ["required"],
            "color" => ["required"],
            "total_seats" => ["required"],
            "rental_price" => ["required"],
            "description" => ["required"],
            "terms" => ["required"],

            "condition" => ["required"],

            "has_driver" => ["required"],

        ]);
        if ($request->hasFile('image')) {
            if ($request->file('image')) {

                $request->validate(["image" => ['image', 'mimes:png,jpeg,gif']]);
                $ext = $request->file('image')->extension();
                $name = Str::random(20);
                $path = $name . "." . $ext;
                $request->file('image')->storeAs('public/images', $path);
                $data['image'] = "images/" . $path;
            }

            if ($vehicle->image) {
                Storage::delete('public/' . $vehicle->image);
            }

            $vehicle->update($data);
            return response()->json(['message' => 'Vehicle Created sucessfully']);
        }
    }

    public function showType(Type $type)
    {
        $type->load('vehicles');
        return response()->json($type);
    }


    public function updateType(Request $request, Type $type)
    {
        $data = $request->validate([
            'name' => ['required']
        ]);
        $type->update($data);
        return response()->json(['message' => 'Vehicle type updated sucessfully']);
    }

    public function addType(Request $request)
    {
        $data = $request->validate([
            'name' => ["required"]
        ]);
        Type::create($data);
        return response()->json(['message' => 'Vehicle type Created sucessfully']);
    }

    public function locations()
    {
        return response()->json(Location::class(['id', 'name']));
    }

    function revehicles(Request $request)
    {
        if (!auth()->user()->vendor) {
            return response()->json(['error' => "Vendor details not found"], 404);
        }

        $vehicles = auth()->user()->vendor->vehicles;

        return response()->json($vehicles);
    }
}
