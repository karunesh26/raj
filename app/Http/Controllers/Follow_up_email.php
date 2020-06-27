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

class Follow_up_email extends Controller
{
	public $table="follow_up_email";
	public $primary_id="id";
	public $field = "email";
	public $msgName = "Follow-Up Email";
	public $view = "follow_up_email";
	public $controller = "Follow_up_email";
	public $module_name = "follow_up_email";
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
			$data['delete_permission'] =  $permission[0]->delete;
		}
		$data['role_id'] = $this->role_id;
		$data['utility'] = $this->utility;
		$data['result'] = Data_model::db_query("select ".$this->table.".*,zone_master.zone_name from ".$this->table." INNER JOIN zone_master ON zone_master.zone_id = ".$this->table.".zone ");
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
		$data['zone']=Data_model::retrive('zone_master','*',array('delete_status'=>0),'zone_name','ASC');
		
		$check_zone = Data_model::retrive($this->table,'zone',array(),'zone','ASC');
		$zone_id = array();
		foreach($check_zone as $key=>$val)
		{
			$zone_id[]=$val->zone;
		}
		$data['zone_id'] = $zone_id;
		$data['msgName']=$this->msgName;
		$data['primary_id']=$this->primary_id;
		$data['field']=$this->field;
		return view($this->view.'/form',$data);
	}
	
	public function insert(Request $request)
	{
		$zone  = $request->input('zone');
		$email_id  = $request->input('email_id');
	
		$data = array('zone'=>$zone,$this->field=>$email_id);
		
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
		$whereData = array(array($this->primary_id, '<>', $id));
		$check_zone=Data_model::retrive($this->table,'zone',$whereData,'zone','ASC');
		$zone_id = array();
		foreach($check_zone as $key=>$val)
		{
			$zone_id[]=$val->zone;
		}
		$data['zone_id'] = $zone_id;
		$data['zone']=Data_model::retrive('zone_master','*',array('delete_status'=>0),'zone_name','ASC');
		$data['result'] = Data_model::retrive($this->table,'*',array($this->primary_id=>$id),$this->primary_id);
		return view($this->view.'/form',$data);
	}
	
	public function update(Request $request)
	{
		$id = $request->input("id");
		$zone  = $request->input('zone');
		$email_id  = $request->input('email_id');
	
		$data = array('zone'=>$zone,$this->field=>$email_id);
		$where = array($this->primary_id=>$id);
		Data_model::restore($this->table,$data,$where);
		$msg = array('success' => $this->msgName.' Updated Sucessfully');
		return redirect($this->controller)->with($msg);
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
