<?php

namespace App\Http\Controllers;

use App\User;
use App\Mail\UserMail;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class UsersController extends Controller
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

	public function list(Request $req)
	{
    $users = DB::table('users')
            ->select('id','name', 'username','email','phone','status','role')
            ->where('status', '!=', 2)
            ->orderBy('role', 'desc')
            ->paginate(10);

    $user_auth = DB::table('users')
            ->select('id')
            ->where('id', '=', Auth::id())
            ->get();

    $login = DB::table('login')->first();

		return view('core.pages.manage.users_manager', compact('users','user_auth','login'));
	}

    public function updateRole(Request $req)
    {
      $resp = [
        "state" => false
      ];

      $user_id = $req->input("user");
      $user_role = $req->input("role");

      $validate = $req->validate([
        "user" => "required",       
        "role" => "required"
      ]);

      if (!isset($validate->errors)) {
        $resp["data"] = [
          "user" => $user_id,       
          "role" => $user_role
        ];

        $user = User::find($user_id);
        $user->role = $user_role;
        $user->save();

        $resp["state"] = true;
      } else {
        $resp["errors"] = $validate->errors;
      }

      return response()->json($resp);
    }


    public function create(Request $req)
    {

      $login = DB::table('login')->first();
      $main_name = $login->app_name;
      
      $resp = [
        "state" => false
      ];

      $full_name = $req->input("fullname");
      $username = $req->input("usern");
      $user_phone = $req->input("phone");
      $user_email = $req->input("email");
      $user_pass = $req->input("password");
      $user_role = $req->input("role");
      $user_status = $req->input("status");

      $name_l = Auth::user()->name;

      $validate = $req->validate([
        "fullname" => "required|max:255",       
        "usern" => "required|max:255",       
        "phone" => "required",    
        "email" => "required|email|max:255|unique:users",
        "password" => "required|min:6",
        "role" => "required",
        "status" => "required"
      ]);

      if (!isset($validate->errors)) {
        $resp["data"] = [
          "fullname" => $full_name,       
          "usern" => $username,       
          "phone" => $user_phone,    
          "email" => $user_email,
          "password" => $user_pass,
          "role" => $user_role,
          "status" => $user_status,
        ];

        $dataMeet = [     
          "user_l" => $name_l, 
          "usern" => $username,
          "email" => $user_email,       
          "password" => $user_pass,
          "app_name" => $main_name
        ];

        $subject_f = "User invitation";

        $user = new User();
        $user->name = $full_name;
        $user->username = $username;
        $user->email = $user_email;
        $user->phone = $user_phone;
        $user->password = bcrypt($user_pass);
        $user->status = $user_status;
        $user->role = $user_role;
        $user->remember_token = str_random(60);
        $user->save();

        Mail::to($user_email)->send(new UserMail($dataMeet,$subject_f));

        $resp["state"] = true;
      } else {
        $resp["errors"] = $validate->errors;
      }

      return response()->json($resp);
    }


    public function updateUser(Request $req)
    {
      $resp = [
        "state" => false
      ];

      $user_id = $req->input("id_u");
      $full_name = $req->input("fullname");
      $username = $req->input("usern");
      $user_phone = $req->input("phone");
      $user_email = $req->input("email");
      $user_pass = $req->input("password");
      $user_role = $req->input("role");
      $user_status = $req->input("status");

      $validate = $req->validate([
        "id_u" => "required", 
        "fullname" => "required|max:255",       
        "usern" => "required|max:255",       
        "phone" => "required",    
        "email" => "required|email|max:255",
        "password" => "nullable|min:6",
        "role" => "required",
        "status" => "required"
      ]);

      if (!isset($validate->errors)) {
        $resp["data"] = [
          "id_u" => $user_id,       
          "fullname" => $full_name,       
          "usern" => $username,       
          "phone" => $user_phone,    
          "email" => $user_email,
          "password" => $user_pass,
          "role" => $user_role,
          "status" => $user_status,
        ];

        $user = User::find($user_id);
        $user->name = $full_name;
        $user->username = $username;
        $user->email = $user_email;
        $user->phone = $user_phone;
        $user->status = $user_status;
        $user->role = $user_role;

        if ($user_pass != null) {
            $user->password = bcrypt($user_pass);
        }

        $user->save();

        $resp["state"] = true;
      } else {
        $resp["errors"] = $validate->errors;
      }

      return response()->json($resp);
    }

    public function destroy($id){

      $post = User::find($id);

      if($post){
        $post->status = '2';
        $post->save();
        return response()->json(['success'=>"user deleted successfully.", 'tr'=>'tr_'.$id]);
      }else{
        return response()->json(['error'=>"This user does not exist."]);
      }
    }

    public function deleteAll(Request $request)
    {
        $ids = $request->ids;
        $selected1 = DB::table("users")->whereIn('id',explode(",",$ids));
        $selected2 = DB::table("users")->whereIn('id',explode(",",$ids))->get();

       if(count($selected2) > 0){
         $selected1->update(['status' => 2]);
         return response()->json(['success'=>"Users deleted successfully."]);
       }else{
         return response()->json(['error'=>"This users does not exist."]);
       }

    }

} 
