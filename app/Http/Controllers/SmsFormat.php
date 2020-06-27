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
class SmsFormat extends Controller
{
	public $table="sms_format";
	public $primary_id="id";
	public $msgName = "SMS Format";
	public $view = "sms_format";
	public $controller = "SmsFormat";
	public $module_name = "sms_format";
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
				$format = $request->input($value->type);
				$data = array('format'=>$format,'updated_by'=>$this->user_id);
				$where = array('type'=>$value->type);
				Data_model::restore($this->table,$data,$where);
		
		}
		
	
			$msg = array('success' => $this->msgName.' Updated Sucessfully');
			
		
		return redirect($this->controller)->with($msg);
	}
	
	
}
