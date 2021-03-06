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

class Project_division_master extends Controller
{
	public $table="project_division_master";
	public $primary_id="project_division_id";
	public $field = "project_division_name";
	public $msgName = "Project Division";
	public $view = "project_division_master";
	public $controller = "Project_division_master";
	public $module_name = "project_division_master";
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
		$data['utility'] = $this->utility;
		$data['result'] = Data_model::retrive($this->table,'*',array('delete_status'=>0),$this->field,'ASC');
		$data['controller_name'] = $this->controller;
		$data['msgName']=$this->msgName;
		$data['primary_id']=$this->primary_id;
		$data['field']=$this->field;
		return view($this->view.'/manage',$data);
	}
	
	public function add()
	{
		  
		$data['action']="insert";
		$data['controller_name'] = $this->controller;
		$data['msgName']=$this->msgName;
		$data['primary_id']=$this->primary_id;
		$data['field']=$this->field;
		return view($this->view.'/form',$data);
	}
	
	public function insert(Request $request)
	{
		$name  = trim(ucwords($request->input('name')));
	
		
		$data = array($this->field=>$name);
		
		if(Data_model::store($this->table,$data))
		{
			$msg = array('success' => $this->msgName.' Added Sucessfully');
			
		}
		else
		{
			$msg = array('error' => 'Some Problem ... Please Try Again');
		}
		return redirect($this->controller)->with($msg);
	}
	
	public function edit($id)
	{
		$data['utility'] = $this->utility;
		$id = $this->utility->decode($id);
		$data['action']="update";
		$data['controller_name'] = $this->controller;
		$data['msgName']=$this->msgName;
		$data['primary_id']=$this->primary_id;
		$data['field']=$this->field;
		$data['result'] = Data_model::retrive($this->table,'*',array($this->primary_id=>$id),$this->primary_id);
		return view($this->view.'/form',$data);
	}
	
	public function update(Request $request)
	{
		$id = $request->input("id");
		$name  = trim(ucwords($request->input('name')));
		
		
		$data = array($this->field=>$name);
		$where = array($this->primary_id=>$id);
		Data_model::restore($this->table,$data,$where);
		
			$msg = array('success' => $this->msgName.' Updated Sucessfully');
			
		
		return redirect($this->controller)->with($msg);
	}
	
	public function duplicate(Request $request)
	{
		$name  = trim(ucwords($request->input('name')));
		$check = Data_model::retrive($this->table,'*',array($this->field=>$name,'delete_status'=>0),$this->primary_id);
	
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
		$whereData = array(array($this->field, $name),array($this->primary_id, '<>', $id),'delete_status'=>0);
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
		$data = array('delete_status'=>1);
		$where = array($this->primary_id=>$id);
		Data_model::restore($this->table,$data,$where);
		$msg = array('success' => $this->msgName.' Deleted Sucessfully');
		return redirect($this->controller)->with($msg);
	}
}
