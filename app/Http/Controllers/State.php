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
class State extends Controller
{
	public $table="state_master";
	public $primary_id="state_id";
	public $foreign_table = "zone_master";
	public $foreign_id = "zone_id";
	public $field = "state_name";
	public $msgName = "State";
	public $view = "state";
	public $controller = "State";
	public $module_name = "state";
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
		$data['result'] = Data_model::db_query("SELECT ".$this->foreign_table.".`zone_name`,`country_master`.`country_name`,`".$this->table."`.* FROM `".$this->table."` 
		LEFT JOIN `".$this->foreign_table."` ON `".$this->foreign_table."`.`".$this->foreign_id."` = `".$this->table."`.`".$this->foreign_id."`
		LEFT JOIN `country_master` ON `country_master`.`country_id` = `".$this->table."`.`country_id` WHERE `".$this->table."`.`delete_status`=0 ORDER BY `state_name`");
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
		$data['zone']=Data_model::retrive($this->foreign_table,'*',array('delete_status'=>0),'zone_name','ASC');
		$data['country']=Data_model::retrive('country_master','*',array('zone_id'=>0,'delete_status'=>0),'country_name','ASC');
		$data['msgName']=$this->msgName;
		$data['primary_id']=$this->primary_id;
		$data['field']=$this->field;
		return view($this->view.'/form',$data);
	}
	
	public function insert(Request $request)
	{
		$name  = trim(ucwords($request->input('name')));
		$zone_id  = trim($request->input('zone_id'));
		$country_id  = trim($request->input('country_id'));
		$data = array($this->field=>$name,'zone_id'=>$zone_id,'country_id'=>$country_id);
		
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
		$data['zone']=Data_model::retrive($this->foreign_table,'*',array('delete_status'=>0),'zone_name','ASC');
		$data['country']=Data_model::retrive('country_master','*',array('zone_id'=>0,'delete_status'=>0),'country_name','ASC');
		$data['result'] = Data_model::retrive($this->table,'*',array($this->primary_id=>$id),$this->primary_id);
		return view($this->view.'/form',$data);
	}
	
	public function update(Request $request)
	{
		$id = $request->input("id");
	
		$name  = trim(ucwords($request->input('name')));
		$zone_id  = trim($request->input('zone_id'));
		$country_id  = trim($request->input('country_id'));
		
		$data = array($this->field=>$name,'zone_id'=>$zone_id,'country_id'=>$country_id);
		$where = array($this->primary_id=>$id);
		Data_model::restore($this->table,$data,$where);
		$city_data = array('zone_id'=>$zone_id);
		$city_where = array('state_id'=>$id);
		Data_model::restore('city_master',$city_data,$city_where);

		$inquiry_id = DB::table('inquiry')
		->join('customer_master', 'inquiry.customer_id', '=', 'customer_master.customer_id')
		->join('state_master', 'customer_master.state_id', '=', 'state_master.state_id')
		->where('customer_master.state_id',$id)
		->update(['inquiry.project_zone' => $zone_id]);

		$inquiry_id = DB::table('quatation')
		->join('inquiry', 'quatation.inquiry_id', '=', 'inquiry.inquiry_id')
		->join('customer_master', 'inquiry.customer_id', '=', 'customer_master.customer_id')
		->join('state_master', 'customer_master.state_id', '=', 'state_master.state_id')
		->where('customer_master.state_id',$id)
		->update(['quatation.zone_id' => $zone_id]);

		$inquiry_id = DB::table('quatation_master')
		->join('inquiry', 'quatation_master.inquiry_id', '=', 'inquiry.inquiry_id')
		->join('customer_master', 'inquiry.customer_id', '=', 'customer_master.customer_id')
		->join('state_master', 'customer_master.state_id', '=', 'state_master.state_id')
		->where('customer_master.state_id',$id)
		->update(['quatation_master.zone_id' => $zone_id]);
			// foreach($inquiry_id as $i_id)
			// {
			// 		$i_data = array('project_zone'=>$zone_id);
			// 		$i_where = array('inquiry_id'=>$i_id->inquiry_id);
			// 		Data_model::restore('inquiry',$i_data,$i_where);
			// 		$q_data = array('zone_id'=>$zone_id);
			// 		$q_where = array('inquiry_id'=>$i_id->inquiry_id);
			// 		Data_model::restore('quatation',$q_data,$q_where);
			// 		Data_model::restore('quatation_master',$q_data,$q_where);
			// }
		//  print_r($inquiry_id);
		// exit;
		$msg = array('success' => $this->msgName.' Updated Sucessfully');
		return redirect($this->controller)->with($msg);
	}
	
	public function duplicate(Request $request)
	{
		$name  = trim(ucwords($request->input('name')));
		$country_id = $request->input("country_id");
		$whereData = array(array($this->field, $name),array('country_id',$country_id),'delete_status'=>0);
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
		$country_id = $request->input("country_id");
		$name  = trim(ucwords($request->input('name')));
		$whereData = array(array($this->field, $name),array('country_id',$country_id),array($this->primary_id, '<>', $id),'delete_status'=>0);
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
		Data_model::restore('city_master',$data,$where);
		$msg = array('success' => $this->msgName.' Deleted Sucessfully');
		return redirect($this->controller)->with($msg);
	}
}
