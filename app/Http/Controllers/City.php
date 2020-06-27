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
class City extends Controller
{
	public $table="city_master";
	public $primary_id="city_id";
	public $field = "city_name";
	public $foreign_table = "state_master";
	public $foreign_id = "state_id";
	public $msgName = "City";
	public $view = "city";
	public $controller = "City";
	public $module_name = "city";
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
		
		$data['result'] = Data_model::db_query("SELECT `".$this->foreign_table."`.`state_name`,`zone_master`.`zone_name`,`country_master`.`country_name`,`".$this->table."`.* FROM `".$this->table."` 
		LEFT JOIN `".$this->foreign_table."` ON `".$this->foreign_table."`.`".$this->foreign_id."` = `".$this->table."`.`".$this->foreign_id."`
		LEFT JOIN `zone_master` ON `zone_master`.`zone_id` = `".$this->table."`.`zone_id`
		LEFT JOIN `country_master` ON `country_master`.`country_id` = `state_master`.`country_id` WHERE `".$this->table."`.`delete_status` = 0 ORDER BY `state_name` ASC,`city_name` ASC");
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
		$data['zone'] = Data_model::retrive('zone_master','*',array('delete_status'=>0),'zone_name','ASC');
		$data['country'] = Data_model::retrive('country_master','*',array('zone_id'=>0,'delete_status'=>0),'country_name','ASC');
		$data['state'] = Data_model::retrive('state_master','*',array('delete_status'=>0),'state_name','ASC');
		return view($this->view.'/form',$data);
	}
	
	public function insert(Request $request)
	{
		$name  = trim(ucwords($request->input('name')));
		$state = $request->input('state');
		$zone = $request->input('zone');
		$data = array($this->field=>$name,'state_id'=>$state,'zone_id'=>$zone);
		
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
		$data['zone'] = Data_model::retrive('zone_master','*',array('delete_status'=>0),'zone_name','ASC');
		$data['country'] = Data_model::retrive('country_master','*',array('zone_id'=>0,'delete_status'=>0),'country_name','ASC');
		$data['state'] = Data_model::retrive('state_master','*',array('delete_status'=>0,'zone_id'=>$data['result'][0]->state_id),'state_name','ASC');
		return view($this->view.'/form',$data);
	}
	
	public function update(Request $request)
	{
		
		$id = $request->input("id");
		$name  = trim(ucwords($request->input('name')));
		
		$state = $request->input('state');
		$zone = $request->input('zone');
		$data = array($this->field=>$name,'state_id'=>$state,'zone_id'=>$zone);
		$where = array($this->primary_id=>$id);
		Data_model::restore($this->table,$data,$where);
		
			$msg = array('success' => $this->msgName.' Updated Sucessfully');
			
		
		return redirect($this->controller)->with($msg);
	}
	
	public function duplicate(Request $request)
	{
		$state = $request->input("state");
		$name  = trim(ucwords($request->input('name')));
		
		$whereData = array(array($this->field, $name),array('state_id',$state),'delete_status'=>0);
		
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
	
	public function duplicate_update(Request $request)
	{
		$id = $request->input("id");
		$state = $request->input("state");
		$name  = trim(ucwords($request->input('name')));
		$whereData = array(array($this->field, $name),array($this->primary_id,'!=', $id),array('state_id',$state),'delete_status'=>0);
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
	
	public function get_state(Request $request)
	{
		$zone = $request->input('zone');
		$data['get_state'] = Data_model::retrive('state_master','*',array('zone_id'=>$zone,'delete_status'=>0),'state_id');
		return view($this->view.'/get_state',$data);
	
	}
	public function check_state(Request $request)
	{
		$statename = $request->input('statename');
		$get_state =DB::table('state_master')
               	 ->where('state_name', 'like', '%'.$statename.'%')
               	 ->where('delete_status', '=', '0')
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
		$country  = trim($request->input('country'));
		$check = Data_model::retrive('state_master','*',array('country_id'=>$country,'state_name'=>$name),'state_id');
	
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
		$country = $request->input('country');
		$name  = trim(ucwords($request->input('statename')));
		$stateredirect = $request->input('stateredirect');
		$data = array('zone_id'=>$zone,'country_id'=>$country,'state_name'=>$name);
		Data_model::store('state_master',$data);
		return redirect($stateredirect);
	}
}
