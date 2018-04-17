<?php

namespace App\Http\Controllers;

use App\User;
use App\Meetings;
use App\Layouts;
use App\Rooms;
use App\RoomsLayout;
use App\Schedules;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class RoomsController extends Controller
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

	public function roomList(Request $req)
	{
    $layouts = Layouts::all();
    $schedule = Schedules::all();


    $rooms = DB::table('rooms')
              ->join('rooms_layouts', 'rooms.id', '=', 'rooms_layouts.room_id')
              ->select('rooms.id','rooms.name', 'rooms.color', 'rooms.capacity','rooms.opening_time', 'rooms.closing_time', 'rooms.status', DB::raw('GROUP_CONCAT(DISTINCT rooms_layouts.layout_id) AS layout'))
              ->groupBy('rooms.id')
              ->paginate(10);

    $login = DB::table('login')->first();

		return view('core.pages.manage.rooms', compact('layouts','schedule','rooms','login'));
	}

  public function create(Request $req)
  {
    $resp = [
      "state" => false
    ];

    $room_name = $req->input("name");
    $room_capacity = $req->input("capacity");
    $room_color = $req->input("color");
    $room_layout = $req->input("layout");
    $room_opening = $req->input("opening");
    $room_closing = $req->input("closing");
    $room_status = $req->input("status");

    $validate = $req->validate([
      "name" => "required",       
      "capacity" => "required",       
      "color" => "required",    
      "layout" => "required",
      "opening" => "required",
      "closing" => "required",
      "status" => "required"
    ]);

    if (!isset($validate->errors)) {
      $resp["data"] = [
        "name" => $room_name,       
        "capacity" => $room_capacity,       
        "color" => $room_color,    
        "layout" => $room_layout,
        "opening" => $room_opening,
        "closing" => $room_closing,
        "status" => $room_status,
      ];

      $room = new Rooms();
      $room->name = $room_name;
      $room->color = $room_color;
      $room->capacity = $room_capacity;
      $room->opening_time = $room_opening;
      $room->closing_time = $room_closing;
      $room->status = $room_status;
      $room->save();

      for ($i = 0; $i < count($room_layout); $i++) {
        $layout = new RoomsLayout();
        $layout->room_id = $room->id;
        $layout->layout_id = $room_layout[$i];
        $layout->save();
      }

      $resp["state"] = true;
    } else {
      $resp["errors"] = $validate->errors;
    }

    return response()->json($resp);
  }



  public function updateRoom(Request $req)
  {
    $resp = [
      "state" => false
    ];

    $room_id = $req->input("id_r");
    $room_name = $req->input("name");
    $room_capacity = $req->input("capacity");
    $room_color = $req->input("color");
    $room_layout = $req->input("layout");
    $room_opening = $req->input("opening");
    $room_closing = $req->input("closing");
    $room_status = $req->input("status");

    $validate = $req->validate([
      "id_r" => "required",       
      "name" => "required",       
      "capacity" => "required",       
      "color" => "required",    
      "layout" => "required",
      "opening" => "required",
      "closing" => "required",
      "status" => "required"
    ]);

    if (!isset($validate->errors)) {
      $resp["data"] = [
        "id_r" => $room_id,       
        "name" => $room_name,       
        "capacity" => $room_capacity,       
        "color" => $room_color,    
        "layout" => $room_layout,
        "opening" => $room_opening,
        "closing" => $room_closing,
        "status" => $room_status,
      ];

      $room = Rooms::find($room_id);
      $room->name = $room_name;
      $room->color = $room_color;
      $room->capacity = $room_capacity;
      $room->opening_time = $room_opening;
      $room->closing_time = $room_closing;
      $room->status = $room_status;
      $room->save();

      $res = RoomsLayout::where('room_id', '=', $room->id)->delete();

      for ($i = 0; $i < count($room_layout); $i++) {
        $layout = new RoomsLayout();
        $layout->room_id = $room->id;
        $layout->layout_id = $room_layout[$i];
        $layout->save();
      }

      $resp["state"] = true;
    } else {
      $resp["errors"] = $validate->errors;
    }

    return response()->json($resp);
  }

  public function destroy($id){

    $post = Rooms::find($id);

    if($post){
      $post->delete();
      return response()->json(['success'=>"Room deleted successfully.", 'tr'=>'tr_'.$id]);
    }else{
      return response()->json(['error'=>"This Room does not exist."]);
    }
  }

  public function deleteAll(Request $request)
  {
      $ids = $request->ids;
      $selected1 = DB::table("rooms")->whereIn('id',explode(",",$ids));
      $selected2 = DB::table("rooms")->whereIn('id',explode(",",$ids))->get();

     if(count($selected2) > 0){
       $selected1->delete();
       return response()->json(['success'=>"Rooms deleted successfully."]);
     }else{
       return response()->json(['error'=>"This rooms does not exist."]);
     }

  }

}