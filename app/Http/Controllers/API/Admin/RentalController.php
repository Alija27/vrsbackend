<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use Illuminate\Http\Request;

class RentalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rentals = Rental::with(['user', 'vehicle'])->get();
        return response()->json($rentals);
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
            "user_id" => ["required", "exists:users,id"],
            "vehicle_id" => ["required", "exists:vehicles,id"],
            "start_date" => ["required", "date", "after:" . now()/* ->addDays(1)->startOfDay() */],
            "end_date" => ["required", "date", "after:start_date"],
            "destination" => ["required"],
            "is_approved" => ["required"],
            "is_complete" => ["required"],
            "total_amount" => ["required"],
            "remarks" => ["required"],
        ]);

        $data['is_complete'] = $request->is_complete ? true : false;

        Rental::create($data);

        return response()->json(['message' => 'Rental Created Sucessfully']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Rental  $rental
     * @return \Illuminate\Http\Response
     */
    public function show(Rental $rental)
    {
        $rental->load(['vehicle', 'user']);
        return response()->json($rental);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Rental  $rental
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Rental $rental)
    {
        $data = $request->validate([
            "user_id" => ["required", "exists:users,id"],
            "vehicle_id" => ["required", "exists:vehicles,id"],
            "start_date" => ["required", "date", "after:" . now()/* ->addDays(1)->startOfDay() */],
            "end_date" => ["required", "date", "after:start_date"],
            "destination" => ["required"],
            "is_approved" => ["required"],
            "is_complete" => ["required"],
            "total_amount" => ["required"],
            "remarks" => ["required"],
        ]);
        $rental->update($data);
        return response()->noContent();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Rental  $rental
     * @return \Illuminate\Http\Response
     */
    public function destroy(Rental $rental)
    {
        $rental->delete();
    }
}
