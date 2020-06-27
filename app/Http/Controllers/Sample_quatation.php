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

class Sample_quatation extends Controller
{
	public $table="sample_quatation";
	public $primary_id="sq_id";
	public $foreign_table = "inquiry";
	public $foreign_id = "inquiry_id";
	public $msgName = "Sample Quatation";
	public $view = "sample_quatation";
	public $controller = "Sample_quatation";
	public $module_name = "sample_quatation";
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
		$data['result'] = Data_model::retrive($this->table,'*',array('delete_status'=>0),'name','ASC');
		$data['controller_name'] = $this->controller;
		$data['msgName']=$this->msgName;
		$data['primary_id']=$this->primary_id;
		return view($this->view.'/manage',$data);
	}
	
	public function add()
	{
		  
		$data['action']="insert";
		$data['controller_name'] = $this->controller;
		$data['msgName']=$this->msgName;
		$data['primary_id']=$this->primary_id;
		$data['specification'] = Data_model::retrive('specification_master','*',array('delete_status'=>0),'specification_id','DESC');
		$data['quatation_product'] = Data_model::retrive('quatation_product','*',array('delete_status'=>0),'p_id','DESC');
		return view($this->view.'/form',$data);
	}
	
	public function insert(Request $request)
	{
		$name  = trim(ucwords($request->input('name')));
		if(!empty($request->specification_id))
		{
			$specification_id = implode("*****",$request->specification_id);
			$specification_arr = $request->specification_id;
			
			$spe_name_arr = array();
			$spe_value_arr = array();
			$sequence_arr = array();
			foreach($specification_arr as $key=>$value)
			{
				
				$sequence_arr[].= $request->input($value."_seqence");
				$nm = $request->input($value."_spe_name");
				$spe_name_arr[].= implode("+++++",$nm);

				$val = $request->input($value."_spe_value");
				$spe_value_arr[].= implode("+++++",$val);
			}
			$spe_name = implode("*****",$spe_name_arr);
			$spe_value = implode("*****",$spe_value_arr);
			
		}
		else
		{
			$specification_id = '';
			$spe_name = '';
			$spe_value = '';
			
		}
		$quatation_product_id = implode(',',$request->quatation_product_id);
		$rate = implode(',',$request->rate);
		$qty = implode(',',$request->qty);
		$amount = implode(',',$request->amount);
		/* $gross_amount = $request->gross_amount;
		$gst_amount = $request->gst_amount; */
		$total_amount = $request->total_amount;
		$user_id = Session::get('raj_user_id');
		$data = array(
				'name'=>$name,
				'quatation_product_id'=>$quatation_product_id,
				'rate'=>$rate,
				'qty'=>$qty,
				'amount'=>$amount,
				/* 'gross_amount'=>$gross_amount,
				'gst_amount'=>$gst_amount, */
				'total_amount'=>$total_amount,
				'specification_id'=>$specification_id,
				'spe_name'=>$spe_name,
				'spe_value'=>$spe_value,
				'added_by'=>$user_id,
				'added_time'=>date('Y-m-d H:i:s'));
		
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
		$data['result'] = Data_model::retrive($this->table,'*',array($this->primary_id=>$id),$this->primary_id);
		$data['controller_name'] = $this->controller;
		$data['msgName']=$this->msgName;
		$data['primary_id']=$this->primary_id;
		$data['specification'] = Data_model::retrive('specification_master','*',array('delete_status'=>0),'specification_id','DESC');
		$data['quatation_product'] = Data_model::retrive('quatation_product','*',array('delete_status'=>0),'p_id','DESC');
		return view($this->view.'/form',$data);
	}
	
	public function update(Request $request)
	{
		
		$id = $request->input("id");
		$name  = trim(ucwords($request->input('name')));
		if(!empty($request->specification_id))
		{
			$specification_id = implode("*****",$request->specification_id);
			$specification_arr = $request->specification_id;
			
			$spe_name_arr = array();
			$spe_value_arr = array();
			$sequence_arr = array();
			foreach($specification_arr as $key=>$value)
			{
				
				$sequence_arr[].= $request->input($value."_seqence");
				$nm = $request->input($value."_spe_name");
				$spe_name_arr[].= implode("+++++",$nm);

				$val = $request->input($value."_spe_value");
				$spe_value_arr[].= implode("+++++",$val);
			}
			$spe_name = implode("*****",$spe_name_arr);
			$spe_value = implode("*****",$spe_value_arr);
			
		}
		else
		{
			$specification_id = '';
			$spe_name = '';
			$spe_value = '';
			
		}
		$quatation_product_id = implode(',',$request->quatation_product_id);
		$rate = implode(',',$request->rate);
		$qty = implode(',',$request->qty);
		$amount = implode(',',$request->amount);
		/* $gross_amount = $request->gross_amount;
		$gst_amount = $request->gst_amount; */
		$total_amount = $request->total_amount;
		$user_id = Session::get('raj_user_id');
		$data = array(
				'name'=>$name,
				'quatation_product_id'=>$quatation_product_id,
				'rate'=>$rate,
				'qty'=>$qty,
				'amount'=>$amount,
				/* 'gross_amount'=>$gross_amount,
				'gst_amount'=>$gst_amount, */
				'total_amount'=>$total_amount,
				'specification_id'=>$specification_id,
				'spe_name'=>$spe_name,
				'spe_value'=>$spe_value,
				'updated_by'=>$user_id);
		Data_model::restore($this->table,$data,array($this->primary_id=>$id));
		$msg = array('success' => $this->msgName.' Updated Sucessfully');
		return redirect($this->controller)->with($msg);
	}
	
	public function duplicate(Request $request)
	{
		$id  = trim($request->input('id'));
		$name  = trim(ucwords($request->input('name')));
		$action  = trim($request->input('action'));
		if($action=='insert')
			$whereData = array('name'=>$name,'delete_status'=>0);
		else
			$whereData = array(array('name',$name),array($this->primary_id, '<>', $id),'delete_status'=>0);
		
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
	public function get_rate(Request $request)
	{
		$quatation_product_id = $request->quatation_product_id;
		$get_rate = Data_model::retrive('quatation_product','*',array('p_id'=>$quatation_product_id),'p_id','');
		if(count($get_rate))
		{
			$data[0]=$get_rate[0]->rate;
		}
		else
		{
			$data[0]=0;
		}
		echo json_encode($data);
	}
	public function view($id)
	{
		$data['utility'] = $this->utility;
		$data['msg'] = $this->msgName;
		$quatation_id = $this->utility->decode($id);
		
		$data['result'] = Data_model::db_query("SELECT `".$this->table."`.* ,`product_master`.`product_name`,`role_master`.`role_name` , `users`.`username` , `users`.`mobile` as `user_mobile` FROM ".$this->table."
		LEFT JOIN `product_master` ON `product_master`.`product_id` = `".$this->table."`.`quatation_product_id`
		LEFT JOIN `users` ON `users`.`id` = `".$this->table."`.`added_by`
		LEFT JOIN `role_master` ON `role_master`.`role_id` = `users`.`role_id`
		where `".$this->table."`.`".$this->primary_id."` = '".$quatation_id."'");
		
		$data['specification'] = Data_model::retrive('specification_master','*',array(),'sequence','ASC');
		$data['quatation_product'] = Data_model::retrive('quatation_product','*',array(),'name','ASC');
		$data['terms_condition'] = Data_model::retrive('terms_condition','*',array(),'term_id','ASC');
		$data['company'] = Data_model::retrive('company','*',array(),'name','ASC');
		$data['controller_name'] = $this->controller;
		$data['msgName']=$this->msgName;
		return view($this->view.'/view',$data);
	}
	
}
