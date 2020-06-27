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


class Employee_transfer extends Controller
{
	public $table="emp_transfer";
	public $primary_id="id";
	public $msgName = "Employee Work Transfer";
	public $view = "emp_transfer";
	public $controller = "Employee_transfer";
	public $module_name = "employee_transfer";
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
		$data['result'] =Data_model::retrive($this->table,'*',array(),$this->primary_id,'DESC');
		$data['emp_detail']  = DB::table('employee')->pluck('username','emp_id');
		$data['zone_detail']  = DB::table('zone_master')->pluck('zone_name','zone_id');


		$data['controller_name'] = $this->controller;
		$data['msgName']=$this->msgName;
		$data['primary_id']=$this->primary_id;

		return view($this->view.'/manage',$data);
	}

	public function add()
	{
		$data['employee'] =Data_model::retrive('employee','*',array(),'emp_id','DESC');
		$data['zones']  = DB::table('zone_master')->where('delete_status',0)->get();
		$data['action']="insert";
		$data['controller_name'] = $this->controller;
		$data['msgName']=$this->msgName;
		$data['primary_id']=$this->primary_id;
		return view($this->view.'/form',$data);
	}

	public function insert(Request $request)
	{
		$zone_id  = implode(",",$request->input('zone_id'));
		$transfer_emp_id  = $request->input('transfer_emp_id');
		$from_date  = date("Y-m-d",strtotime($request->input('from_date')));
		$to_date  = date("Y-m-d",strtotime($request->input('to_date')));

		$data = array('zone_id'=>$zone_id,'transfer_emp_id'=>$transfer_emp_id,'transfer_from_date'=>$from_date,'transfer_to_date'=>$to_date);

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
}
