<?php

namespace App\Http\Controllers;

use App\User;
use App\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ResourcesController extends Controller
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
    $resources = DB::table('resources')->paginate(10);

    $login = DB::table('login')->first();

		return view('core.pages.manage.resources', compact('resources','login'));
	}

  public function create(Request $req)
  {
    $resp = [
      "state" => false
    ];

    $resource_name = $req->input("name");
    $resource_description = $req->input("description");
    $resource_icon = $req->input("icon");
    $resource_status = $req->input("status");

    $validate = $req->validate([
      "name" => "required",       
      "description" => "required",       
      "icon" => "required",    
      "status" => "required"
    ]);

    if (!isset($validate->errors)) {
      $resp["data"] = [
        "name" => $resource_name,       
        "description" => $resource_description,       
        "icon" => $resource_icon,    
        "status" => $resource_status
      ];

      $resource = new Resources();
      $resource->name = $resource_name;
      $resource->description = $resource_description;
      $resource->icon = $resource_icon;
      $resource->status = $resource_status;
      $resource->save();

      $resp["state"] = true;
    } else {
      $resp["errors"] = $validate->errors;
    }

    return response()->json($resp);
  }


  public function updateResource(Request $req)
  {
    $resp = [
      "state" => false
    ];

    $resource_id = $req->input("id_r");
    $resource_name = $req->input("name");
    $resource_description = $req->input("description");
    $resource_icon = $req->input("icon");
    $resource_status = $req->input("status");

    $validate = $req->validate([
      "id_r" => "required",       
      "description" => "required",       
      "icon" => "required",    
      "status" => "required"
    ]);

    if (!isset($validate->errors)) {
      $resp["data"] = [
        "id_r" => $resource_id,       
        "description" => $resource_description,       
        "icon" => $resource_icon,    
        "status" => $resource_status
      ];

      $resource = Resources::find($resource_id);
      $resource->name = $resource_name;
      $resource->description = $resource_description;
      $resource->icon = $resource_icon;
      $resource->status = $resource_status;
      $resource->save();

      $resp["state"] = true;
    } else {
      $resp["errors"] = $validate->errors;
    }

    return response()->json($resp);
  }



  public function destroy($id){

    $post = Resources::find($id);

    if($post){
      $post->delete();
      return response()->json(['success'=>"Resource deleted successfully.", 'tr'=>'tr_'.$id]);
    }else{
      return response()->json(['error'=>"This Resource does not exist."]);
    }
  }

  public function deleteAll(Request $request)
  {
      $ids = $request->ids;
      $selected1 = DB::table("resources")->whereIn('id',explode(",",$ids));
      $selected2 = DB::table("resources")->whereIn('id',explode(",",$ids))->get();

     if(count($selected2) > 0){
       $selected1->delete();
       return response()->json(['success'=>"Resources deleted successfully."]);
     }else{
       return response()->json(['error'=>"This resources does not exist."]);
     }

  }

}