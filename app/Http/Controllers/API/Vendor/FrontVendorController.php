<?php

namespace App\Http\Controllers\API\Vendor;

use App\Models\Type;
use App\Models\Rental;
use App\Models\Vendor;
use App\Models\Vehicle;
use App\Models\Location;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User as ModelsUser;
use App\Notifications\BookingConfirmationNotification;
use App\Notifications\CanceledBookingNotification;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use App\Notifications\VehicleAvailable;
use Illuminate\Support\Facades\Storage;
use App\Notifications\NewVehicleRequest;
use Illuminate\Support\Facades\Notification;

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

    public function vehicleApproved(Request $request, Rental $rental)
    {
        $data = $request->validate([
            "is_approved" => ['required'],
        ]);
        $data = $rental->update([
            'is_approved' => $request->is_approved
        ]);

        if ($request->is_approved == "Confirmed") {
            $user = ModelsUser::find($rental->user_id);
            $user->notify(new BookingConfirmationNotification($rental));
        }
        if ($request->is_approved == "Canceled") {
            $user = ModelsUser::find($rental->user_id);
            $user->notify(new CanceledBookingNotification($rental));
        }
        /*  $user = User::find(Rental::find($data["user_id"]));
        $user->notify(new NewVehicleRequest); */
        return response()->json(['message' => "Status Updated"]);
    }



    public function addVehicle(Request $request)
    {
        $data = $request->validate([
            "name" => ["required"],
            "vendor_id" => ["required", "exists:users,id"],
            "type_id" => ["required", "exists:types,id"],
            "model" => ["required"],
            "color" => ["required"],
            "brand" => ["required"],
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
            "brand" => ["required"],
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
        }
        $vehicle->update($data);
        return response()->json(['message' => 'Vehicle Updated sucessfully']);
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

    public function getRequestList(Request $request)
    {
        if (!auth()->user()->vendor) {
            return response()->json(['error' => "Vendor details not found"], 404);
        }

        $vehicles = auth()->user()->vendor->vehicles;
        $vehicle_ids = $vehicles->pluck('id')->toArray();

        $rentals = Rental::with(['user', 'vehicle'])->whereIn('vehicle_id', $vehicle_ids)->orderBy('id', 'DESC')->get();

        return response()->json($rentals);
    }

    public  function revehicles(Request $request)
    {
        if (!auth()->user()->vendor) {
            return response()->json(['error' => "Vendor details not found"], 404);
        }

        $vehicles = auth()->user()->vendor->vehicles;

        return response()->json($vehicles);
    }

    /* public  function revehiclesbyID(Request $request)
    {
        if (!auth()->user()->vendor) {
            return response()->json(['error' => "Vendor details not found"], 404);
        }

        $vehicles = auth()->user()->vendor->vehicles;

        return response()->json($vehicles);
    } */
    public function revehicleid(Vehicle $vehicle)
    {
        $vehicle->load(['vendor', 'type', 'location']);
        return response()->json($vehicle);
    }

    public function VendorEdit(Request $request, Vendor $vendor)
    {
        $data = $request->validate([
            "user_id" => ["required"],
            "name" => ["required"],
            "phone" => ["required", "numeric", "min:10", 'regex:/((98)|(97))([0-9]){8}/'],
            "address" => ["required"],

        ]);
        if ($request->file('image')) {
            $ext = $request->file('image')->extension();
            $name = Str::random(20);
            $path = $name . "." . $ext;
            $request->file('image')->storeAs('public/images', $path);
            $data['image'] = "images/" . $path;

            if ($vendor->image) {
                Storage::delete('public/' . $vendor->image);
            }
        }

        $vendor->update($data);
        return response()->json(["message" => "Vendor"]);
    }
    public function vendorId(Vendor $vendor)
    {
        $vendor->load(['vehicles']);
        return response()->json($vendor);
    }
}
