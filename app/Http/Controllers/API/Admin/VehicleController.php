<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vehicles = Vehicle::all();
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
            "image" => ["required"],
            "condition" => ["required"],
            "is_available" => ["required"],
            "has_driver" => ["required"],
            "is_approved" => ["required"],
        ]);
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
            "image" => ["required"],
            "condition" => ["required"],
            "is_available" => ["required"],
            "has_driver" => ["required"],
            "is_approved" => ["required"],
        ]);
        $vehicle->update($data);
        return response()->noContent();
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
