<?php

namespace App\Http\Controllers\API;

use App\Models\Type;
use App\Models\User;
use App\Models\Rental;
use App\Models\Review;
use App\Models\Vendor;
use App\Models\Contact;
use App\Models\Vehicle;
use App\Models\Location;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Notifications\VehicleRequestSent;
use App\Notifications\BookingConfirmationNotification;
use App\Notifications\NewVehicleRequest;
use App\Notifications\VendorAccountRegistrationRequestSent;
use Carbon\Carbon;

class FrontController extends Controller
{



    public function contact(Request $request)
    {
        $data = $request->validate([
            "name" => "required",
            "email" => "required",
            "phonenumber" => ["required", "numeric", "min:10", 'regex:/((98)|(97))([0-9]){8}/'],
            "message" => "required",
        ]);
        Contact::create($data);
        return response()->json(["message" => "Message sent sucessfully"]);
    }

    public function frequentlyUsedVehicles()
    {
        $vehicles = Rental::with('vehicle')->selectRaw('count(vehicle_id) as number_of_bookings, vehicle_id')->groupBy('vehicle_id')->orderByDesc('number_of_bookings')->take(5)->get();
        return $vehicles;
    }

    public function allReviews()
    {
        $reviews = Review::with('vehicle')->get();
        return $reviews;
    }

    public function types()
    {
        return response()->json(Type::select(['id', 'name', 'image'])->get());
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
            "phone" => ["required", "numeric", "min:10", 'regex:/((98)|(97))([0-9]){8}/'],
            "address" => ["required"],
            "password" => ["required"],
            "image" => ["required", 'image', 'mimes:png,jpeg,gif'],
            "citizenship_number" => ["required"],
            "citizenship_image" => ["required", "citizenship_image", "mimes:png,jpeg,gif"],
        ]);

        if ($request->file('image')) {
            $ext = $request->file('image')->extension();
            $name = Str::random(20);
            $path = $name . "." . $ext;
            $request->file('image')->storeAs('public/images', $path);
            $data['image'] = "images/" . $path;
        }
        if ($request->file('citizenship_image')) {
            $ext = $request->file('citizenship_image')->extension();
            $name = Str::random(20);
            $path = $name . "." . $ext;
            $request->file('citizenship_image')->storeAs('public/images', $path);
            $data['citizenship_image'] = "images/" . $path;
        }

        User::create($data);
        return response()->json(['message' => 'User Created  Sucessfully']);
    }
    public function VendorRegister(Request $request)
    {
        $data = $request->validate([
            "user_id" => ["required", "unique:vendors,user_id"],
            "name" => ["required"],
            "phone" => ["required", "numeric", "min:10", 'regex:/((98)|(97))([0-9]){8}/'],
            "address" => ["required"],
            "image" => ["required", "mimes:jpg,png,jpeg,gif"]
        ]);
        if ($request->file('image')) {
            $ext = $request->file('image')->extension();
            $name = Str::random(20);
            $path = $name . "." . $ext;
            $request->file('image')->storeAs('public/images', $path);
            $data['image'] = "images/" . $path;
        }
        Vendor::create($data);
        $user = User::find($data["user_id"]);
        $user->notify(new VendorAccountRegistrationRequestSent);
        return response()->json(["message" => "Your registration request has sent to admin. You will be notify soon"]);
    }

    public function updateProfile(Request $request, User $user)
    {
        $data = $request->validate([
            "name" => ["required"],
            "email" => ["required", "email"],
            "phone" => ["required", "numeric", "min:10", 'regex:/((98)|(97))([0-9]){8}/'],
            "addrress" => ["required"],
            "password" => ["required"],
            "image" => ["required",  'mimes:png,jpgeg,gif'],
            "citizenship_number" => ["required"],
            "citizenship_image" => ["required",  "mimes:png,jpeg,gif"],

        ]);
        if ($request->file('image')) {
            $ext = $request->file('image')->extension();
            $name = Str::random(20);
            $path = $name . "." . $ext;
            $request->file('image')->storeAs('public/images', $path);
            $data['image'] = "images/" . $path;

            if ($user->image) {
                Storage::delete('public/' . $user->image);
            }
        }


        if ($request->file('citizenship_image')) {
            $ext = $request->file('citizenship_image')->extension();
            $name = Str::random(20);
            $path = $name . "." . $ext;
            $request->file('citizenship_image')->storeAs('public/images', $path);
            $data['citizenship_image'] = "images/" . $path;

            if ($user->citizenship_image) {
                Storage::delete('public/' . $user->citizenship_image);
            }
        }

        $user->update($data);
        return response()->json(['message' => 'User updated  Sucessfully']);
    }


    public function showUser(User $user)
    {
        $user->load('vendor');

        return response()->json($user);
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
    public function notification(Vendor $vendor)
    {

        $data = Vendor::where('status', 'Accepted')->count();


        return response()->json($data);
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
        $vehicle->load(['vendor', 'type', 'location']);
        return response()->json($vehicle);
    }
    public function availableVehicles()
    {
        $vehicles = Vehicle::where('is_available', true)->get();
        return response()->json($vehicles);
    }

    public function CancelBooking(Request $request, Rental $rental)
    {
        $request->validate([
            "is_approved" => ['required'],
        ]);
        $rental->update([
            'is_approved' => $request->is_approved
        ]);
        return response()->json(['message' => "Status Updated"]);
    }

    public function checkVehicle(Request $request, Vehicle $vehicle)
    {
        $request->validate([
            'start_date' => ['required', 'date', 'after:' . now()],
            'end_date' => ['required', 'date', 'after:start_date'],
        ]);

        // $start_date = $request->date('start_date');
        // $end_date = $request->date('end_date');

        $start_date = Carbon::parse($request->start_date);
        $end_date = Carbon::parse($request->end_date);

        if (!$vehicle->is_available) {
            return response()->json([
                'status' => 'Vehicle is not available!',
                'is_available' => false,
            ]);
        }

        $rentals = Rental::whereBetween('start_date', [$start_date, $end_date])
            ->orWhereBetween('end_date', [$start_date, $end_date])->get()
            ->pluck('vehicle_id')
            ->toArray();

        $vehicles = Vehicle::whereNotIn('id', $rentals)->get()->pluck('id')->toArray();

        if (in_array($vehicle->id, $vehicles)) {
            $total = $end_date->diffInDays($start_date) * $vehicle->rental_price;
            if ($total == 0) {
                $total = $vehicle->rental_price;
            }
            return response()->json([
                'status' => 'Vehicle is available!',
                'total_price' => $total,
                'is_available' => true,
            ]);
        } else {
            return response()->json([
                'status' => 'Vehicle is not available for given time period.',
                'is_available' => false,
            ]);
        }
    }

    public function requestVehicle(Request $request, User $user)
    {
        /*  $query = User::query();

        if (!empty($request->citizenship_number)) {
            $query = $query->where('citizenship_number', $request->citizenship_number);
        }
        if (!empty($request->citizenship_image)) {
            $query = $query->where('citizenship_image', $request->citizenship_image);
        } else {
        } */

        $data = request()->validate([
            "user_id" => ["required"],
            "vehicle_id" => ["required", "exists:vehicles,id"],
            "start_date" => ["required", "date", "after:" . now()/* ->addDays(1)->startOfDay() */],
            "end_date" => ["required", "date", "after:start_date"],
            "destination" => ["required"],
            "total_amount" => ["required"],
            "remarks" => ["required"],
        ]);


        Rental::create($data);
        $user = User::find($data["user_id"]);
        $user->notify(new VehicleRequestSent());
        $vendor = User::find(Vendor::find(Vehicle::find($data['vehicle_id'])->vendor_id)->user_id);
        $vendor->notify(new NewVehicleRequest);
        return response()->json("Requested Sucessfully");
    }

    /* public function checkUserDetails()
    {
        $user = auth()->user();

        if (empty($user->citizenship_number) || empty($user->citizenship_image)) {
            return response()->json([

                'Citizenship not found!',
            ], 412);
        }

        return response()->noContent();
    } */

    public function  VehicleReview(Vehicle $vehicle)
    {
        return response()->json($vehicle->reviews);
    }

    public function myBookedVehicles()
    {
        $rentals = Rental::with('vehicle')->where('user_id', auth()->user()->id)->orderBy('id', 'DESC')->get();

        return response()->json($rentals);
    }
    public function userId(User $user)
    {

        return response()->json($user);
    }
    public function postReview(Request $request)
    {
        $data = $request->validate([
            "user_id" => ["required"],
            "vehicle_id" => ["required"],
            "message" => ["required"],
            "stars" => ["required"]
        ]);

        Review::create($data);
        return response()->json(["message" => "Review Posted"]);
    }

    public function eligibleForReview($id)
    {
        // $vehicle = Vehicle::find($id);
        $rentals = Rental::where('vehicle_id', $id)->where('user_id', auth()->user()->id)->where('is_approved', 'Completed')->count();
        return $rentals;
    }


    public function getReview(Vehicle $vehicle)
    {
        $data = Review::with(['vehicle', 'user'])->where('vehicle_id', $vehicle->id)->get();
        return response()->json($data);
    }
}
