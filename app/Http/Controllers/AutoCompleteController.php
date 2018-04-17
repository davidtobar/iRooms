<?php

namespace App\Http\Controllers;

use App\Meetings;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AutoCompleteController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
	    $this->middleware('auth');
	}

	public function autoComplete(Request $request)
	{
	    $query = $request->get('term','');
	    
	    $meetings= Meetings::where('status', 1)->whereName($query)->orWhere('name','LIKE','%'.$query.'%')->orderBy('start_date','DESC')->limit(5)->get();
	    
	    $data=array();

	    foreach ($meetings as $meeting) {
	            $data[]=array('value'=>$meeting->name,'id'=>$meeting->id,'start_date'=>$meeting->start_date);
	    }

	    if(count($data))
	         return $data;
	    else
	        return ['value'=>'No Result Found'];

	}

	public function searchData(Request $req)
	{
		$meetId = Input::get('id');
		$meetStart = Input::get('start');

	    $meetSearch = DB::table('meetings')
	          ->join('rooms', 'meetings.room', '=', 'rooms.id')
	          ->join('users', 'meetings.user_id', '=', 'users.id')
	          ->where('meetings.id', '=', $meetId)
	          ->where('meetings.status', '=', 1)
	          ->where('users.status', '=', 1)
	          ->where('meetings.start_date', '=', $meetStart)
	          ->select('meetings.id', 'meetings.name AS meeting_name', 'meetings.start_date', 'meetings.start_time','meetings.end_date', 'meetings.end_time','meetings.description','meetings.room','meetings.status','rooms.name AS room_name','rooms.color','rooms.capacity','users.name AS user_name')
	          ->orderBy('meetings.start_date', 'desc')
	          ->orderBy('meetings.start_time', 'asc')
	          ->get();

	    return response($meetSearch);

	}


}