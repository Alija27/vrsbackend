<?php

namespace App\Http\Controllers\API\Admin;

use App\Models\Type;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class TypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $types = Type::with('vehicles')->get();
        return response()->json($types);
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
            'name' => ["required"],
            'image' => ["required", "image", "mimes:png,jpg"]
        ]);
        $ext = $request->file('image')->extension();
        $name = Str::random(20);
        $path = $name . "." . $ext;

        $request->file('image')->storeAs('public/images', $path);
        $data['image'] = "images/" . $path;
        Type::create($data);
        return response()->json(['message' => 'Vehicle type Created sucessfully']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function show(Type $type)
    {
        $type->load('vehicles');
        return response()->json($type);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Type $type)
    {
        $data = $request->validate([
            'name' => ['required'],
            'image' => ["required", "image", "mimes:png,jpg"]
        ]);
        if ($request->hasFile('image')) {
            $request->validate(["image" => ['image', 'mimes:png,jpeg,gif']]);
            $ext = $request->file('image')->extension();
            $ext = $request->file('image')->extension();
            $name = Str::random(20);
            $path = $name . "." . $ext;

            $request->file('image')->storeAs('public/images', $path);
            $data['image'] = "images/" . $path;

            if ($type->image) {
                Storage::delete('public/' . $type->image);
            }
        }
        $type->update($data);
        return response()->json(['message' => 'Vehicle type updated sucessfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function destroy(Type $type)
    {
        $type->delete();
        return response()->json(['message' => 'Vehicle type deleted  sucessfully']);
    }
}
