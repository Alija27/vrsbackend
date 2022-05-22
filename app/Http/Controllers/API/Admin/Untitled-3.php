<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Room;
use App\Models\Roomtype;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\Types\Boolean;
use Psr\Http\Message\ResponseInterface;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $room = Book::all();
        return response()->json($room);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'room_req' => ['required', 'gte:1'],
            'start_date' => ['bail', 'required',  'required_with:end_date', 'after_or_equal:' . now(),  'date', 'before_or_equal:end_date'],
            'end_date' => ['bail', 'required', 'required_with:start_date', 'date', 'after_or_equal:start_date'],
            'roomtype_id' => ['required'],
        ]);
        $start_date = Carbon::parse($request->start_date);
        $end_date = Carbon::parse($request->end_date);
        $data = ['user_id' => Auth::user()->id, 'room_id' => $request->room_id, 'roomtype_id' => $request->roomtype_id, 'room_req' => $request->room_req,];
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        //Counting room
        $room = Room::where('roomtype_id', '=', $data['roomtype_id'])->count();
        // return $room;
        // return $count_book_room;
        $booking_rooms = Book::where('roomtype_id', $request->roomtype_id)
            ->whereBetween('start_date', [$start_date, $end_date])
            ->orWhereBetween('end_date', [$start_date, $end_date])
            ->get()
            ->pluck('roomtype_id')
            ->count();
        // return $booking_rooms;


        $room_available = $room - $booking_rooms;
        // return $room_available;
        if ($room_available < $data['room_req']) {
            return response()->json(['message' => $room_available . ' room available!!!']);
        }
        if ($room_available == 0) {
            return response()->json(['message' => 'no room available']);
        } else {
            Book::create($data);
            $room_available = $room_available - $data['room_req'];
            return response()->json(['message' => 'Room booked Sucessfully']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function show(Book $book)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Book $book)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function destroy(Book $book)
    {
        //
    }
}