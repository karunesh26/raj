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
class Assign_zone extends Controller
{
	public $table="state_master";
	public $primary_id="state_id";
	public $msgName = "Assign Zone";
	public $view = "assign_zone";
	public $controller = "Assign_zone";
	public $module_name = "assign_zone";
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
				$add_per = $permission[0]->add;
				if($add_per != 1)
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
		$data['role_id'] = $this->role_id;
		$data['utility'] = $this->utility;
		$data['action']="update";
		$data['controller_name'] = $this->controller;
		$data['msgName']=$this->msgName;
		$data['primary_id']=$this->primary_id;
		$data['zone'] = Data_model::retrive('zone_master','*',array(),'zone_id','DESC');
		$data['state'] = Data_model::retrive('state_master','*',array('zone_id'=>0),'state_id','DESC');
		return view($this->view.'/form',$data);
	}
	public function update(Request $request)
	{
		$zone = $request->input('zone');
		$state = $request->input('state');
		foreach($state as $val)
		{
			$state_data = array('zone_id'=>$zone);
			$state_where = array($this->primary_id=>$val);
			Data_model::restore($this->table,$state_data,$state_where);
			
			$city_data = array('zone_id'=>$zone);
			$city_where = array($this->primary_id=>$val);
			Data_model::restore('city_master',$state_data,$state_where);
		}
		$msg = array('success' => $this->msgName.' Sucessfully');
		return redirect($this->controller)->with($msg);
	}
	
}
