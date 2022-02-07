<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Favorite;
use App\Models\Booking;
use App\Http\Requests\BookingRequest;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = Auth::id();
        $today = date('Y-m-d');
        $time = date('H:i:s',strtotime('1hour'));
        $bookings = Booking::with('shop:id,name')
        ->where('user_id', $user_id)
        ->where(function($query) use($today,$time){
            $query->where([
                ['date', '=', $today],
                ['time', '>', $time],
                ])
                ->orWhere('date', '>', $today);
        })
        ->orderBy('date','asc')
        ->orderBy('time','asc')
        ->get();
        return response()->json([
            'data' => $bookings
        ],201 );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BookingRequest $request)
    {
        $data = Booking::create($request->all());
        return response()->json([
            'data' => $data
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = Booking::find($id)->delete();
        if ($item) {
            return response()->json([
            'message' => 'Deleted successfully',
        ], 200);
        } else {
            return response()->json([
            'message' => 'Not found',
        ], 404);
        }
    }
}
