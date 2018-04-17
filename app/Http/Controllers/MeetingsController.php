<?php

namespace App\Http\Controllers;

use App\Meetings;
use App\RoomsLayout;
use App\Layouts;
use App\Rooms;
use App\Resources;
use App\MeetingsResources;
use App\Schedules;
use App\Mail\MeetingMail;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class MeetingsController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function create(Request $req)
    {
      $resp = [
        "state" => false
      ];

      $meeting_name = $req->input("name");
      $meeting_date = $req->input("date");
      $meeting_room = $req->input("room");
      $meeting_layout = $req->input("layout");
      $meeting_start_time = $req->input("start_time");
      $meeting_end_time = $req->input("end_time");
      $meeting_description = $req->input("description");

      $validate = $req->validate([
        "name" => "required",       
        "date" => "required",       
        "room" => "required",    
        "layout" => "required",    
        "start_time" => "required",        
        "end_time" => "required",
        "description" => "required"
      ]);

      if (!isset($validate->errors)) {
        $resp["data"] = [
          "name" => $meeting_name,       
          "date" => $meeting_date,       
          "room" => $meeting_room,    
          "layout" => $meeting_layout,    
          "start_time" => $meeting_start_time,        
          "end_time" => $meeting_end_time,
          "description" => $meeting_description
        ];

        $startDate = new \DateTime($meeting_date);
        $meeting_date = $startDate->format("Y-m-d");

        $meeting = new Meetings();
        $meeting->name = $meeting_name;
        $meeting->start_date = $meeting_date;
        $meeting->start_time = $meeting_start_time;
        $meeting->end_date = $meeting_date;
        $meeting->end_time = $meeting_end_time;
        $meeting->description = $meeting_description;
        $meeting->room = $meeting_room;
        $meeting->layout = $meeting_layout;
        $meeting->user_id = Auth::id();
        $meeting->save();

        $resp["state"] = true;
      } else {
        $resp["errors"] = $validate->errors;
      }

      return response()->json($resp);
    }

    public function layouts(){
      $rooms_id = Input::get('room_id');
      $roomsLayout = RoomsLayout::where('room_id', '=', $rooms_id)->get();

      return response()->json($roomsLayout);

    }

    public function advancedCreate(){
      $rooms = Rooms::where('status', 1)
               ->get();
      $resources = Resources::where('status', 1)
                   ->get();

      $login = DB::table('login')->first();

      return view('core.pages.schedule.advanced_booking', compact('rooms', 'resources','login'));
    }

    public function advanced(Request $req)
    {

      $login = DB::table('login')->first();
      $main_name = $login->app_name;

      $resp = [
        "state" => false
      ];

      $meeting_name = $req->input("name");
      $meeting_room = $req->input("room");
      $meeting_room_name = $req->input("room_name");
      $meeting_start_date = $req->input("start_date");
      $meeting_start_time = $req->input("start_time");
      $meeting_end_date = $req->input("end_date");
      $meeting_end_time = $req->input("end_time");
      $meeting_mom_start = $req->input("mom_start");
      $meeting_mom_end = $req->input("mom_end");
      $meeting_description = $req->input("description");
      $meeting_notify = $req->input("notify");
      $meeting_noti = $req->input("noti");
      $meeting_layout = $req->input("layout");
      $meeting_resource = $req->input("resource");

      $validate = $req->validate([
        "name" => "required",       
        "room" => "required",    
        "room_name" => "required",    
        "start_date" => "required",    
        "start_time" => "required",        
        "end_date" => "required",    
        "end_time" => "required",        
        "mom_start" => "required",        
        "mom_end" => "required",        
        "description" => "required",
        // "notify" => "required",
        // "noti" => "required",
        "layout" => "required",
        "resource" => "required"
      ]);

      if (!isset($validate->errors)) {
        $resp["data"] = [
          "name" => $meeting_name,       
          "room" => $meeting_room,       
          "room_name" => $meeting_room_name,       
          "start_date" => $meeting_start_date,        
          "start_time" => $meeting_start_time,        
          "end_date" => $meeting_end_date,
          "end_time" => $meeting_end_time,
          "mom_start" => $meeting_mom_start,
          "mom_end" => $meeting_mom_end,
          "description" => $meeting_description,
          "notify" => $meeting_notify,
          "noti" => $meeting_noti,
          "layout" => $meeting_layout,
          "resource" => $meeting_resource
        ];

        $dataMeet = [
          "name" => $meeting_name,       
          "room" => $meeting_room,
          "room_name" => $meeting_room_name,       
          "start_date" => $meeting_start_date,        
          "start_time" => $meeting_start_time,        
          "end_date" => $meeting_end_date,
          "end_time" => $meeting_end_time,
          "mom_start" => $meeting_mom_start,
          "mom_end" => $meeting_mom_end,
          "description" => $meeting_description,
          "notify" => $meeting_notify,
          "noti" => $meeting_noti,
          "layout" => $meeting_layout,
          "resource" => $meeting_resource,
          "app_name" => $main_name
        ];

        $subject_f = "New Meeting: " .$meeting_room_name. " on ".$meeting_start_date. " from ".$meeting_mom_start. " to ".$meeting_mom_end;

        $startDate = new \DateTime($meeting_start_date);
        $meeting_start_date = $startDate->format("Y-m-d");

        $endDate = new \DateTime($meeting_end_date);
        $meeting_end_date = $endDate->format("Y-m-d");

        $meeting = new Meetings();
        $meeting->name = $meeting_name;
        $meeting->start_date = $meeting_start_date;
        $meeting->start_time = $meeting_start_time;
        $meeting->end_date = $meeting_end_date;
        $meeting->end_time = $meeting_end_time;
        $meeting->description = $meeting_description;
        $meeting->people_notify = $meeting_notify;
        $meeting->room = $meeting_room;
        $meeting->layout = $meeting_layout;
        $meeting->user_id = Auth::id();
        $meeting->save();

        for ($i = 0; $i < count($meeting_resource); $i++) {
          $resources = new MeetingsResources();
          $resources->meeting_id = $meeting->id;
          $resources->resources_id = $meeting_resource[$i];
          $resources->save();
        }

        if ($meeting_noti[0] != "") {
          for ($i = 0; $i < count($meeting_noti); $i++) {
            
            try {
              Mail::to($meeting_noti[$i])->send(new MeetingMail($dataMeet,$subject_f));
            } catch (\Exception $ex) {
              $resp["errors"] = $ex;
            }

          }
        }

        $resp["state"] = true;
      } else {
        $resp["errors"] = $validate->errors;
      }

      return response()->json($resp);
    }

    public function roomLayouts(){

      $rooms_id = Input::get('room_id');

      $roomsLayout = DB::table('rooms_layouts')
            ->join('layouts', 'rooms_layouts.layout_id', '=', 'layouts.id')
            ->where('rooms_layouts.room_id', '=', $rooms_id)
            ->select('rooms_layouts.room_id', 'rooms_layouts.layout_id', 'layouts.name','layouts.img')
            ->get();

      return response()->json($roomsLayout);

    }

    public function validateHours(){
      $rooms_id = Input::get('room_id');

      $rooms = Rooms::find($rooms_id); 

      $open_time = $rooms->opening_time;
      $close_time = $rooms->closing_time;

      $o_time = date('H:i', strtotime($open_time));
      $c_time = date('H:i', strtotime($close_time));

      $select = DB::table('schedules')
                ->where('hours', '>=', $o_time)
                ->where('hours', '<=', $c_time)
                ->get();

      return response()->json($select);
    }

    /*POPUP*/
    public function firstOption(){
      $start_date = Input::get('start');
      $room_selected = Input::get('room');


      $startDate = new \DateTime($start_date);
      $meeting_start_date = $startDate->format("Y-m-d");
      
      $same_date = DB::table('meetings')
                ->where('room', '=', $room_selected)
                ->where('start_date', '=', $meeting_start_date)
                ->where('end_date', '!=', $meeting_start_date)
                ->get();

      return response()->json($same_date);
    }

    public function sameOptions(){
      $start_date = Input::get('start');
      $room_selected = Input::get('room');


      $startDate = new \DateTime($start_date);
      $meeting_start_date = $startDate->format("Y-m-d");
      
      $select = DB::table('meetings')
                ->where('room', '=', $room_selected)
                ->where('start_date', '=', $meeting_start_date)
                ->where('end_date', '=', $meeting_start_date)
                ->get();

      return response()->json($select);
    }

    public function lastOption(){
      $start_date = Input::get('start');
      $room_selected = Input::get('room');

      $startDate = new \DateTime($start_date);
      $meeting_start_date = $startDate->format("Y-m-d");
      
      $second_val = DB::table('meetings')
                ->where('room', '=', $room_selected)
                ->where('start_date', '!=', $meeting_start_date)
                ->where('end_date', '=', $meeting_start_date)
                ->get();

      return response()->json($second_val);
    }


    /*When same date*/
    public function dateRange(){
      $start_date = Input::get('start');
      $end_date = Input::get('end');
      $room_selected = Input::get('room');


      $startDate = new \DateTime($start_date);
      $meeting_start_date = $startDate->format("Y-m-d");

      $endDate = new \DateTime($end_date);
      $meeting_end_date = $endDate->format("Y-m-d");
      
      $select = DB::table('meetings')
                ->where('room', '=', $room_selected)
                ->where('start_date', '=', $meeting_start_date)
                ->where('end_date', '=', $meeting_end_date)
                ->get();

      return response()->json($select);
    }

    public function sameDate(){
      $start_date = Input::get('start');
      $room_selected = Input::get('room');


      $startDate = new \DateTime($start_date);
      $meeting_start_date = $startDate->format("Y-m-d");
      
      $same_date = DB::table('meetings')
                ->where('room', '=', $room_selected)
                ->where('start_date', '=', $meeting_start_date)
                ->where('end_date', '!=', $meeting_start_date)
                ->get();

      return response()->json($same_date);
    }


    public function sameEnd(){
      $end_date = Input::get('end');
      $room_selected = Input::get('room');

      $endDate = new \DateTime($end_date);
      $meeting_end_date = $endDate->format("Y-m-d");
      
      $second_val = DB::table('meetings')
                ->where('room', '=', $room_selected)
                ->where('start_date', '!=', $meeting_end_date)
                ->where('end_date', '=', $meeting_end_date)
                ->get();

      return response()->json($second_val);
    }


    /* When diferent date*/

    public function diferentStartdate(){
      $start_date = Input::get('start');
      $room_selected = Input::get('room');


      $startDate = new \DateTime($start_date);
      $meeting_start_date = $startDate->format("Y-m-d");
      
      $diferent_s = DB::table('meetings')
                ->where('room', '=', $room_selected)
                ->where('start_date', '=', $meeting_start_date)
                ->where('end_date', '=', $meeting_start_date)
                ->get();

      return response()->json($diferent_s);
    }

    public function diferentEnd(){
      $end_date = Input::get('end');
      $room_selected = Input::get('room');

      $endDate = new \DateTime($end_date);
      $meeting_end_date = $endDate->format("Y-m-d");
      
      $diferent_e = DB::table('meetings')
                ->where('room', '=', $room_selected)
                ->where('start_date', '=', $meeting_end_date)
                ->where('end_date', '=', $meeting_end_date)
                ->get();

      return response()->json($diferent_e);
    }

    public function onlyvalidateStart(){
      $start_date = Input::get('start');
      $room_selected = Input::get('room');

      $startDate = new \DateTime($start_date);
      $meeting_start_date = $startDate->format("Y-m-d");
      
      $only_start = DB::table('meetings')
                ->where('room', '=', $room_selected)
                ->where('start_date', '!=', $meeting_start_date)
                ->where('end_date', '=', $meeting_start_date)
                ->get();

      return response()->json($only_start);
    }


    public function firstvalDif(){
      $end_date = Input::get('end');
      $room_selected = Input::get('room');


      $endDate = new \DateTime($end_date);
      $meeting_end_date = $endDate->format("Y-m-d");
      
      $first_val = DB::table('meetings')
                ->where('room', '=', $room_selected)
                ->where('start_date', '=', $meeting_end_date)
                ->where('end_date', '!=', $meeting_end_date)
                ->get();

      return response()->json($first_val);
    }

    public function secondvalDif(){
      $end_date = Input::get('end');
      $room_selected = Input::get('room');

      $endDate = new \DateTime($end_date);
      $meeting_end_date = $endDate->format("Y-m-d");
      
      $second_val = DB::table('meetings')
                ->where('room', '=', $room_selected)
                ->where('start_date', '!=', $meeting_end_date)
                ->where('end_date', '=', $meeting_end_date)
                ->get();

      return response()->json($second_val);
    }


    /*Disabled option*/
    public function disabledOption(){
      $start_ti = Input::get('start_datef');
      $start_option = Input::get('start_timef');
      $meet_room = Input::get('room');

      $startTi = new \DateTime($start_ti);
      $meeting_start_date = $startTi->format("Y-m-d");
      
      $final_val = DB::table('meetings')
                ->where('start_date', '=', $meeting_start_date)
                ->where('start_time', '!=', $start_option)
                ->where('room', '=', $meet_room)
                ->get();

      return response()->json($final_val);
    }


    public function edit($id){
      $meetings = Meetings::find($id);
      $rooms = Rooms::where('status', 1)
               ->get();
      $resources = Resources::where('status', 1)
               ->get();

      $layoutEdits = DB::table('rooms_layouts')
            ->join('layouts', 'rooms_layouts.layout_id', '=', 'layouts.id')
            ->where('rooms_layouts.room_id', '=', $meetings->room)
            ->select('rooms_layouts.room_id', 'rooms_layouts.layout_id', 'layouts.name','layouts.img')
            ->get();

      $selected = $meetings->room;
      $sel_layout = $meetings->layout;
      $sel_id = $meetings->id;
      $sel = $meetings->start_time;
      $end = $meetings->end_time;
      $resid = $meetings->end_time;

      $startDate = new \DateTime($meetings->start_date);
      $meeting_start_date = $startDate->format('l, F d Y');

      $endDate = new \DateTime($meetings->end_date);
      $meeting_end_date = $endDate->format("l, F d Y");

      $resourceE = MeetingsResources::where('meeting_id', '=', $sel_id)->get();

      /*validateHours*/ 

      $roomv = Rooms::find($selected);

      $open_time = $roomv->opening_time;
      $close_time = $roomv->closing_time;

      $o_time = date('H:i', strtotime($open_time));
      $c_time = date('H:i', strtotime($close_time));

      $select = DB::table('schedules')
                ->where('hours', '>=', $o_time)
                ->where('hours', '<=', $c_time)
                ->select('id','hours', DB::raw('TIME_FORMAT(hours, "%h:%i %p") as time'))
                ->get();

      $start_t = $meetings->start_time;
      $end_t = $meetings->end_time;

      $start= date('H:i', strtotime($start_t));
      $end= date('H:i', strtotime($end_t));

      $login = DB::table('login')->first();

      return view('core.pages.schedule.edit_booking',compact('meetings','rooms','selected','sel','end','resources', 'resourceE','layoutEdits','sel_layout','meetRes','meeting_start_date','meeting_end_date','select','start', 'end','login'));
    }

    public function update(Request $req, $id)
    {
      $resp = [
        "state" => false
      ];

      $meeting_name = $req->input("name");
      $meeting_room = $req->input("room");
      $meeting_room_name = $req->input("room_name");
      $meeting_start_date = $req->input("start_date");
      $meeting_start_time = $req->input("start_time");
      $meeting_end_date = $req->input("end_date");
      $meeting_end_time = $req->input("end_time");
      $meeting_mom_start = $req->input("mom_start");
      $meeting_mom_end = $req->input("mom_end");
      $meeting_description = $req->input("description");
      $meeting_notify = $req->input("notify");
      $meeting_noti = $req->input("noti");
      $meeting_layout = $req->input("layout");
      $meeting_resource = $req->input("resource");

      $validate = $req->validate([
        "name" => "required",       
        "room" => "required",    
        "room_name" => "required",    
        "start_date" => "required",    
        "start_time" => "required",        
        "end_date" => "required",    
        "end_time" => "required",        
        "mom_start" => "required",        
        "mom_end" => "required",        
        "description" => "required",
        // "notify" => "required",
        // "noti" => "required",
        "layout" => "required",
        "resource" => "required"
      ]);

      if (!isset($validate->errors)) {
        $resp["data"] = [
          "name" => $meeting_name,       
          "room" => $meeting_room,       
          "room_name" => $meeting_room_name,       
          "start_date" => $meeting_start_date,        
          "start_time" => $meeting_start_time,        
          "end_date" => $meeting_end_date,
          "end_time" => $meeting_end_time,
          "mom_start" => $meeting_mom_start,
          "mom_end" => $meeting_mom_end,
          "description" => $meeting_description,
          "notify" => $meeting_notify,
          "noti" => $meeting_noti,
          "layout" => $meeting_layout,
          "resource" => $meeting_resource
        ];

        $dataMeet = [
          "name" => $meeting_name,       
          "room" => $meeting_room,
          "room_name" => $meeting_room_name,       
          "start_date" => $meeting_start_date,        
          "start_time" => $meeting_start_time,        
          "end_date" => $meeting_end_date,
          "end_time" => $meeting_end_time,
          "mom_start" => $meeting_mom_start,
          "mom_end" => $meeting_mom_end,
          "description" => $meeting_description,
          "notify" => $meeting_notify,
          "noti" => $meeting_noti,
          "layout" => $meeting_layout,
          "resource" => $meeting_resource
        ];

        $subject_f = "Update Meeting: " .$meeting_room_name. " on ".$meeting_start_date. " from ".$meeting_mom_start. " to ".$meeting_mom_end;

        $startDate = new \DateTime($meeting_start_date);
        $meeting_start_date = $startDate->format("Y-m-d");

        $endDate = new \DateTime($meeting_end_date);
        $meeting_end_date = $endDate->format("Y-m-d");

        $meeting = Meetings::find($id);
        $meeting->name = $meeting_name;
        $meeting->start_date = $meeting_start_date;
        $meeting->start_time = $meeting_start_time;
        $meeting->end_date = $meeting_end_date;
        $meeting->end_time = $meeting_end_time;
        $meeting->description = $meeting_description;
        $meeting->people_notify = $meeting_notify;
        $meeting->room = $meeting_room;
        $meeting->layout = $meeting_layout;
        $meeting->user_id = Auth::id();
        $meeting->save();

        $res = MeetingsResources::where('meeting_id', '=', $meeting->id)->delete();

        for ($i = 0; $i < count($meeting_resource); $i++) {
          $resources = new MeetingsResources();
          $resources->meeting_id = $meeting->id;
          $resources->resources_id = $meeting_resource[$i];
          $resources->save();
        }

        if ($meeting_noti[0] != "") {
          for ($i = 0; $i < count($meeting_noti); $i++) {

            try {
              Mail::to($meeting_noti[$i])->send(new MeetingMail($dataMeet,$subject_f));
            } catch (\Exception $ex) {
              $resp["errors"] = $ex;
            }

          }
        }

        $resp["state"] = true;
      } else {
        $resp["errors"] = $validate->errors;
      }

      return response()->json($resp);
    }

}
