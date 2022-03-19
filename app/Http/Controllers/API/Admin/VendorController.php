<?php

namespace App\Http\Controllers\API\Admin;

use App\Models\Vendor;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vendors = Vendor::with('user')->get();
        return response()->json($vendors);
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
            "address" => ["required"],
            "phone" => ["required", "numeric", "min:10"],
            "user_id" => ["required", "exists:users,id"],
            "image" => ["required", "image", 'mimes:png,jpeg,gif']
        ]);
        $ext = $request->file('image')->extension();
        $name = Str::random(20);
        $path = $name . "." . $ext;

        $request->file('image')->storeAs('public/images', $path);
        $data['image'] = "images/" . $path;

        Vendor::create($data);
        return response()->json(['message' => 'Vendor Created sucessfully'], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function show(Vendor $vendor)
    {
        $vendor->load('user');
        return response()->json($vendor);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Vendor $vendor)
    {
        $data = $request->validate([
            "name" => ["required"],
            "address" => ["required"],
            "phone" => ["required", "numeric", "min:10"],
            "user_id" => ["required", "exists:users,id"],
        ]);
        if ($request->hasFile('image')) {
            $request->validate(["image" => ['image', 'mimes:png,jpeg,gif']]);
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
        return response()->json(['message' => 'Vendor Created sucessfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vendor $vendor)
    {

        DB::transaction(function () use ($vendor) {
            // DB::delete('delete from vendors where id =' . $vendor->id);
            $vendor->user()->delete();
            $vendor->delete();
        });
        return response()->json(["message" => "Vendor deleted Sucessfully"]);
    }
}
