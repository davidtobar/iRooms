<?php

namespace App\Http\Controllers;

use App\User;
use App\Meetings;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ManagementAccountController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
	    $this->middleware('auth');
      $this->middleware('management');
	}

	public function myAccount(Request $req)
	{
		$name = Auth::user()->name;
		$telephone = Auth::user()->phone;
		$email = Auth::user()->email;

    $login = DB::table('login')->first();

		return view('core.pages.account.management.my_account', compact('email','name','telephone','login'));
	}

	public function editProfile(Request $req)
	{
		$resp = [
        "state" => false
      ];

      $profile_name = $req->input("name");
      $profile_phone = $req->input("phone");
      $profile_email = $req->input("email");

      $validate = $req->validate([
        "name" => "required",       
        "phone" => "required",    
        "email" => "required"
      ]);

      if (!isset($validate->errors)) {
        $resp["data"] = [
          "name" => $profile_name,       
          "phone" => $profile_phone,       
          "email" => $profile_email
        ];

        $userId = Auth::id();

        $user = User::find($userId);
        $user->name = $profile_name;
        $user->email = $profile_email;
        $user->phone = $profile_phone;
        $user->save();

        $resp["state"] = true;
      } else {
        $resp["errors"] = $validate->errors;
      }

      return response()->json($resp);
	}

	public function changePassword(Request $request){

		if (!(\Hash::check($request->get('current-password'), Auth::user()->password))) {
            // The passwords matches
            return redirect()->back()->with("error","Your old password does not matches with the password you provided.");
        }
 
        if(strcmp($request->get('current-password'), $request->get('new-password')) == 0){

            return redirect()->back()->with("error","New Password cannot be same as your old password.");
        }
 
        $validatedData = $request->validate([
            'current-password' => 'required',
            'new-password' => 'required|string|min:6|confirmed',
        ]);
 
        $user = Auth::user();
        $user->password = bcrypt($request->get('new-password'));
        $user->save();
 
        return redirect()->back()->with("success","Password updated successfully.");

	}

    public function myMeetings(Request $request){

      $meetDetail = DB::table('meetings')
            ->join('rooms', 'meetings.room', '=', 'rooms.id')
            ->join('users', 'meetings.user_id', '=', 'users.id')
            ->leftjoin('meeting_resources', 'meetings.id', '=', 'meeting_resources.meeting_id')
            ->leftJoin('resources', 'meeting_resources.resources_id', '=', 'resources.id')
            ->where('meetings.status', '=', 1)
            ->where('users.status', '=', 1)
            ->where('meetings.user_id', '=', Auth::id())
            ->select('meetings.id', 'meetings.name AS meeting_name', DB::raw('DATE_FORMAT(meetings.start_date, "%d %b %Y") as startD') , DB::raw('TIME_FORMAT(meetings.start_time, "%h:%i %p") as time'), DB::raw('HOUR(TIMEDIFF(meetings.end_time, meetings.start_time)) as hour') , DB::raw('MINUTE(TIMEDIFF(meetings.end_time, meetings.start_time)) as minute'), 'rooms.name AS room_name','rooms.color','resources.name AS resource', DB::raw('GROUP_CONCAT(DISTINCT resources.icon) AS icon'))
            ->groupBy('meetings.id')
            ->orderBy('meetings.start_date', 'desc')
            ->paginate(10);

      $login = DB::table('login')->first();

      return view('core.pages.account.management.my_meetings', compact('meetDetail','login'));
    }

    public function destroy($id){

      $post = Meetings::find($id);

      if($post){
        $post->delete();
        return response()->json(['success'=>"Meeting deleted successfully.", 'tr'=>'tr_'.$id]);
      }else{
        return response()->json(['error'=>"This meeting does not exist."]);
      }
    }

    public function deleteAll(Request $request)
    {
        $ids = $request->ids;
        $selected1 = DB::table("meetings")->whereIn('id',explode(",",$ids));
        $selected2 = DB::table("meetings")->whereIn('id',explode(",",$ids))->get();

       if(count($selected2) > 0){
         $selected1->delete();
         return response()->json(['success'=>"Meetings deleted successfully."]);
       }else{
         return response()->json(['error'=>"This meetings does not exist."]);
       }

    }

    public function meetingsApproval(Request $request){

      $meetDetail = DB::table('meetings')
            ->join('rooms', 'meetings.room', '=', 'rooms.id')
            ->join('users', 'meetings.user_id', '=', 'users.id')
            ->leftjoin('meeting_resources', 'meetings.id', '=', 'meeting_resources.meeting_id')
            ->leftJoin('resources', 'meeting_resources.resources_id', '=', 'resources.id')
            ->where('meetings.status', '=', 2)
            ->select('meetings.id', 'meetings.name AS meeting_name', DB::raw('DATE_FORMAT(meetings.start_date, "%d %b %Y") as startD') , DB::raw('TIME_FORMAT(meetings.start_time, "%h:%i %p") as time'), DB::raw('HOUR(TIMEDIFF(meetings.end_time, meetings.start_time)) as hour') , DB::raw('MINUTE(TIMEDIFF(meetings.end_time, meetings.start_time)) as minute'),'meetings.status','rooms.name AS room_name','rooms.color','resources.name AS resource','users.name AS user_name', DB::raw('GROUP_CONCAT(DISTINCT resources.icon) AS icon'))
            ->groupBy('meetings.id')
            ->orderBy('meetings.start_date', 'desc')
            ->paginate(10);

      $login = DB::table('login')->first();

      return view('core.pages.account.management.meetings_approval', compact('meetDetail','login'));
    }

    public function updateStatus(Request $req)
    {
      $resp = [
        "state" => false
      ];

      $meeting_id = $req->input("meeting");
      $meeting_status = $req->input("status");

      $validate = $req->validate([
        "meeting" => "required",      
        "status" => "required"
      ]);

      if (!isset($validate->errors)) {
        $resp["data"] = [
          "meeting" => $meeting_id,       
          "status" => $meeting_status
        ];

        $meeting = Meetings::find($meeting_id);
        $meeting->status = $meeting_status;
        $meeting->save();

        $resp["state"] = true;
      } else {
        $resp["errors"] = $validate->errors;
      }

      return response()->json($resp);
    }

}