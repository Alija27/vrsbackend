<?php

namespace App\Http\Controllers\API\Admin;

use App\Models\Vehicle;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vehicles = Vehicle::with(['vendor', 'type', 'location'])->get();

        return response()->json($vehicles);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
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
            "is_available" => ["required"],
            "has_driver" => ["required"],
            "is_approved" => ["required"],
            "location_id" => ["required"],
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Vehicle  $vehicle
     * @return \Illuminate\Http\Response
     */
    public function show(Vehicle $vehicle)
    {
        $vehicle->load(['vendor', 'type', 'location']);
        return response()->json($vehicle);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Vehicle  $vehicle
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Vehicle $vehicle)
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
            "is_available" => ["required"],
            "has_driver" => ["required"],
            "is_approved" => ["required"],
            "location_id" => ["required"],
        ]);
        if ($request->hasFile('image')) {
            $request->validate(["image" => ['required']]);
            if ($request->image != $vehicle->image) {
                $ext = $request->file('image')->extension();
                $name = Str::random(20);
                $path = $name . "." . $ext;
                $request->file('image')->storeAs('public/images', $path);
                $data['image'] = "images/" . $path;
            }

            if ($vehicle->image) {
                Storage::delete('public/' . $vehicle->image);
            }
        }
        $vehicle->update($data);
        return response()->json(["message" => "Vehicle updated sucessfully"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Vehicle  $vehicle
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();
    }
}
