<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\Data_model;
use App\Libraries\Utility;

use Session;
use DB;
use Redirect;
use Validator;


//set_error_handler(null);
//set_exception_handler(null);
class Minimum_days extends Controller
{
	public $table="minimum_days";
	public $primary_id="id";
	public $msgName = "Minimum Days";
	public $view = "minimum_days";
	public $controller = "Minimum_days";
	public $module_name = "minimum_days";
	public $utility;
	public $role_id;
	public $user_id;
	public function __construct()
    {
		if (!Session::has('raj_user_id')) 
		{
			$msg = array('error' => 'You Must First Login To Access');
			Redirect::to('/')->send()->with($msg);
		}
		
		$this->role_id = Session::get('raj_role_id');
		$this->user_id = Session::get('raj_user_id');
		
		if($this->role_id != '1')
		{
			$permission = Data_model::get_permission($this->module_name);
			if(empty($permission))
			{
				Redirect::to('/')->send();
			}
			else
			{
				$edit_per = $permission[0]->edit;
				if($edit_per != 1)
				{
					Redirect::to('/')->send();
				}
			}
		}
		
		
		date_default_timezone_set("Asia/Kolkata");
		$this->utility = new Utility();
		
	}
	
	public function index()
	{
		if($this->role_id != '1')
		{
			$permission = Data_model::get_permission($this->module_name);
			$data['add_permission'] =  $permission[0]->add;
			$data['edit_permission'] =  $permission[0]->edit;
			$data['print_permission'] =  $permission[0]->print;
			$data['delete_permission'] =  $permission[0]->delete;
		}
		$data['role_id'] = $this->role_id;
		$data['result'] = Data_model::retrive($this->table,'*',array(),$this->primary_id,'ASC');
		$data['action']="update";
		$data['controller_name'] = $this->controller;
		$data['msgName']=$this->msgName;
		return view($this->view.'/form',$data);
	}
	
	
	public function update(Request $request)
	{
		
		$result =  Data_model::retrive($this->table,'*',array(),$this->primary_id,'ASC');
		
		foreach($result as $key=>$value)
		{
				$days = $request->input($value->type);
				$data = array('days'=>$days);
				$where = array('type'=>$value->type);
				Data_model::restore($this->table,$data,$where);
		
		}
		
	
			$msg = array('success' => $this->msgName.' Updated Sucessfully');
			
		
		return redirect($this->controller)->with($msg);
	}
	
	public function duplicate(Request $request)
	{
		$name  = trim(ucwords($request->input('name')));
		$check = Data_model::retrive($this->table,'*',array($this->field=>$name),$this->primary_id);
	
		if (empty($check))
		{
			echo(json_encode(true)); 
   		}	
    	else
		{
        	echo(json_encode(false));
    	}
		
	}
	
	public function duplicate_update(Request $request)
	{
		$id = $request->input("id");
		$name  = trim(ucwords($request->input('name')));
		$whereData = array(array($this->field, $name),array($this->primary_id, '<>', $id));
		$check = Data_model::retrive($this->table,'*',$whereData,$this->primary_id);
	
		if (empty($check))
		{
			echo(json_encode(true)); 
   		}	
    	else
		{
        	echo(json_encode(false));
    	}
	}
	
	public function delete($id)
	{
		$id = $this->utility->decode($id);
		
		$where = array($this->primary_id=>$id);
		if(Data_model::remove($this->table,$where))
		{
			$msg = array('success' => $this->msgName.' Deleted Sucessfully');
			
		}
		else
		{
			$msg = array('error' => 'Some Problem ... Please Try Again');
		}
		return redirect($this->controller)->with($msg);
	}
	
	public function get_state(Request $request)
	{
		$zone = $request->input('zone');
		$get_state = Data_model::retrive('state_master','*',array('zone_id'=>$zone),'state_id');
		 echo '<option  value="">Select</option>';
		  foreach($get_state as $k=> $v)
		  {
		  	 echo '<option value="'.$v->state_id.'" >'.$v->state_name.'</option>';
		  }
           
	}
	public function check_state(Request $request)
	{
		$statename = $request->input('statename');
		$get_state =DB::table('state_master')
               	 ->where('state_name', 'like', '%'.$statename.'%')
               	 ->get();
		 if(!empty($get_state))
		 {
		  echo '<h4 style="margin-left:10px">Following State Already Exist</h4>';
		  foreach($get_state as $k=> $v)
		  {
		  	 echo '<p style="margin-left:10px">'.$v->state_name.'</p>';
		  }
		 }
           
	}
	public function state_duplicate(Request $request)
	{
		$name  = trim(ucwords($request->input('statename')));
		$check = Data_model::retrive('state_master','*',array('state_name'=>$name),'state_id');
	
		if (empty($check))
		{
			echo(json_encode(true)); 
   		}	
    	else
		{
        	echo(json_encode(false));
    	}
		
	}
	public function state_add(Request $request)
	{
		$zone = $request->input('zone');
		$name  = trim(ucwords($request->input('statename')));
		$stateredirect = $request->input('stateredirect');
		$data = array('zone_id'=>$zone,'state_name'=>$name);
		Data_model::store('state_master',$data);
		return redirect($stateredirect);
	}
}
