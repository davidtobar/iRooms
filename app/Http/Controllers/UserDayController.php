<?php

namespace App\Http\Controllers;

use App\Rooms;
use App\Meetings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class UserDayController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('user');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    $rooms = Rooms::where('status', 1)
             ->get();

    $meetings = DB::table('meetings')
            ->join('rooms', 'meetings.room', '=', 'rooms.id')
            ->join('users', 'meetings.user_id', '=', 'users.id')
            // ->where('meetings.status', '=', 1)
            ->where('rooms.status', '=', 1)
            ->where('users.status', '=', 1)
            ->select('meetings.id', 'meetings.name AS meeting_name', 'meetings.start_date', 'meetings.start_time','meetings.end_date', 'meetings.end_time','meetings.description','meetings.room','meetings.status','rooms.name AS room_name','rooms.color','rooms.capacity','users.name AS user_name','users.id AS user_id','users.role')
            ->orderBy('meetings.start_date', 'desc')
            ->orderBy('meetings.start_time', 'asc')
            ->get();

    $countRooms = DB::table('rooms')
          ->where('status', '=', 1)
          ->count('id');
          
    $rooms_week = DB::table('rooms')
                  ->where('status', '=', 1)
                  ->select('id','name','color','capacity','opening_time','closing_time','status')
                  ->orderBy('id', 'asc')
                  ->get();

    $login = DB::table('login')->first();

    return view('core.pages.schedule.user.daily', compact('rooms','meetings','rooms_week','countRooms','login'));

    }


    public function updateMeeting(Request $req)
    {
      $resp = [
        "state" => false
      ];

      $meeting_id = $req->input("id");
      $meeting_end_date = $req->input("end_date");
      $meeting_end_time = $req->input("end_time");

      $validate = $req->validate([
        "id" => "required",       
        "end_date" => "required",    
        "end_time" => "required"
      ]);

      if (!isset($validate->errors)) {
        $resp["data"] = [
          "id" => $meeting_id,       
          "end_date" => $meeting_end_date,  
          "end_time" => $meeting_end_time
        ];

        $meeting = Meetings::find($meeting_id);
        $meeting->end_date = $meeting_end_date;
        $meeting->end_time = $meeting_end_time;
        $meeting->save();

        $resp["state"] = true;
      } else {
        $resp["errors"] = $validate->errors;
      }

      return response()->json($resp);
    }


}
