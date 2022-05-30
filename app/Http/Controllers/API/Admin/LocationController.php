<?php

namespace App\Http\Controllers\API\Admin;

use App\Models\User;
use App\Models\Location;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Notifications\NewLocationAdded;
use Illuminate\Support\Facades\Notification;


class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $locations = Location::with('vehicles')->get();
        return response()->json($locations);
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
            "longitude" => ["required"],
            "latitude" => ["required"],
        ]);

        Location::create($data);
        $users = User::all();
        Notification::send($users, new NewLocationAdded);

        return response()->json(['message' => 'Location Created  Sucessfully']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function show(Location $location)
    {
        $location->load('vehicles');

        return response()->json($location);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Location $location)
    {
        $data = $request->validate([
            "name" => ["required"],
            "latitude" => ["required"],
            "longitude" => ["required"]
        ]);

        $location->update($data);

        return response()->json(['message' => 'Location Updated Sucessfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function destroy(Location $location)
    {
        try {
            $location->delete();
        } catch (\Exception $e) {
            return response()->json(['error' => 'You cannot delete location with related data!'], 401);
        }

        return response()->json(['message' => "Location deleted Sucessfully"]);
    }
}
