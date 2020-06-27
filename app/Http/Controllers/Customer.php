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

class Customer extends Controller
{
	public $table="customer_master";
	public $primary_id="customer_id";
	public $foreign_table="inquiry";
	public $foreign_id="inquiry_id";
	public $msgName = "Customer";
	public $view = "customer";
	public $controller = "Customer";
	public $module_name = "customer";
	public $utility;
	public $role_id;
	public $user_id;

	public function __construct()
    {
		if(!Session::has('raj_user_id'))
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
		$data['utility'] = $this->utility;
		$data['role_id'] = $this->role_id;

		if($this->role_id != '1')
		{
			$permission = Data_model::get_permission($this->module_name);
			$data['edit_permission'] =  $permission[0]->edit;
		}

		$data['controller_name'] = $this->controller;
		$data['msgName']=$this->msgName;
		$data['primary_id']=$this->primary_id;

		return view($this->view.'/manage',$data);
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


		$data['country'] = Data_model::retrive('country_master','*',array('delete_status'=>0),'country_name','ASC');
		$data['state'] = Data_model::retrive('state_master','*',array('delete_status'=>0),'state_name','ASC');
		$data['city'] = Data_model::retrive('city_master','*',array('delete_status'=>0),'city_name','ASC');
		return view($this->view.'/form',$data);

	}

	public function update(Request $request)
	{
		$id = $request->input("id");
		$customer_prefix = $request->input('customer_prefix');
		$name = $request->input('name');
		$company = $request->input('company');
		$country_id  = $request->input('country_id');
		$state_id  = $request->input('state_id');
		$city_id  = $request->input('city_id');
		$mobile  = $request->input('mobile');
		$mtype1 = $request->input('mtype1');
		$mobile_2 = $request->input('mobile_2');
		$mtype2 = $request->input('mtype2');
		$mobile_3 = $request->input('mobile_3');
		$mtype3 = $request->input('mtype3');
		$landline = $request->input('landline');
		$email = $request->input('email');
		$email_2 = $request->input('email_2');
		$address = $request->input('address');
		$office_address = $request->input('office_address');


		$get_country_zone = Data_model::retrive('country_master','*',array('country_id'=>$country_id),'country_id','');

		//Get Zone
		if($get_country_zone[0]->zone_id != 0)
		{
			$zone_id = $get_country_zone[0]->zone_id;
			$state_id = 0;
			$city_id = 0;
		}
		else
		{
			$get_zone = Data_model::retrive('state_master','zone_id',array('state_id'=>$state_id),'state_id','DESC');
			$zone_id = $get_zone[0]->zone_id;
		}


		$data = array('prefix'=>$customer_prefix,
					'name'=>$name,
					'address'=>$address,
					'country_id'=>$country_id,
					'state_id'=>$state_id,
					'city_id'=>$city_id,
					'mobile'=>$mobile,
					'mobile_2'=>$mobile_2,
					'mobile_3'=>$mobile_3,
					'mobile_type1'=>$mtype1,
					'mobile_type2'=>$mtype2,
					'mobile_type3'=>$mtype3,
					'office_address'=>$office_address,
					'landline'=>$landline,
					'company'=>$company,
					'email'=>$email,
					'email_2'=>$email_2);
		$where = array($this->primary_id=>$id);

		Data_model::restore($this->table,$data,$where);

		$get_inq_id = Data_model::retrive('inquiry','inquiry_id',array('customer_id'=>$id),'inquiry_id','DESC');

		if(! empty($get_inq_id))
		{
			$inq_arr = array();
			foreach($get_inq_id as $key=>$val)
			{
				$inq_arr[] = $val->inquiry_id;
			}
			$update_inq_id = implode(",",$inq_arr);

			// Data_model::db_update("update `inquiry` set `project_zone` = ".$zone_id." where inquiry_id IN (".$update_inq_id.") ");
			// Data_model::db_update("update `quatation` set `zone_id` = ".$zone_id." where inquiry_id IN (".$update_inq_id.") ");
			// Data_model::db_update("update `quatation_master` set `zone_id` = ".$zone_id." where inquiry_id IN (".$update_inq_id.") ");
		}

		$msg = array('success' => $this->msgName.' Updated Sucessfully');

		return redirect($this->controller)->with($msg);
	}
	public function mobile_check(Request $request)
	{
		$customer_id  = trim($request->customer_id);
		$mobile  = trim($request->mobile);

		if($customer_id=='')
		{
			$check = Data_model::db_query("SELECT * FROM `customer_master` WHERE `mobile`='$mobile' OR `mobile_2` = '$mobile' OR `mobile_3` = '$mobile'");
			if(count($check))
			{
				echo 1;
			}
			else
			{
				echo 0;
			}
		}
		else
		{
			$check = Data_model::db_query("SELECT * FROM `customer_master` WHERE (`mobile`='$mobile' OR `mobile_2` = '$mobile' OR `mobile_3` = '$mobile') AND `customer_id` != $customer_id");
			if(count($check))
			{
				echo 1;
			}
			else
			{
				echo 0;
			}
		}
	}
	public function email_check(Request $request)
	{
		$customer_id  = trim($request->customer_id);
		$email  = trim($request->email);

		if($customer_id=='')
		{
			$check = Data_model::db_query("SELECT * FROM `customer_master` WHERE `email`='$email' OR `email_2` = '$email'");
			if(count($check))
			{
				echo 1;
			}
			else
			{
				echo 0;
			}
		}
		else
		{
			$check = Data_model::db_query("SELECT * FROM `customer_master` WHERE (`email`='$email' OR `email_2` = '$email') AND `customer_id` != $customer_id");
			if(count($check))
			{
				echo 1;
			}
			else
			{
				echo 0;
			}
		}
	}

	public function get_country_zone(Request $request)
	{
		$country_id  = trim($request->country_id);
		$check = Data_model::retrive("country_master",'*',array('country_id'=>$country_id),'country_id','');
		$state=Data_model::retrive("state_master",'*',array('country_id'=>$country_id,'delete_status'=>0),'state_name','ASC');
		$data[0] = $check[0]->zone_id;
		$data[1]='<option  value="">Select</option>';
		foreach($state as $k=> $v)
		{
			$data[1] .= '<option value="'.$v->state_id.'">'.$v->state_name.'</option>';
		}
		echo json_encode($data);
	}
	public function get_city(Request $request)
	{
		$state_id = $request->input('state_id');
		$get_city = Data_model::retrive('city_master','*',array('state_id'=>$state_id,'delete_status'=>0),'city_name','ASC');
		echo '<option value="">Select</option>';
		foreach($get_city as $k=>$v)
		{
			echo '<option value="'.$v->city_id.'" >'.$v->city_name.'</option>';
		}
	}
	public function get_all_customer(Request $request)
	{
		$data = array("data"=>"");
		$result = Data_model::retrive($this->table,'*',array(),'customer_id','DESC');

		if($result)
		{
			foreach($result as $key=>$val)
			{
				if($this->role_id == 1)
				{
					$action = "<a title='Edit' class='btn bg-purple btn-flat btn-sm' href='".$this->controller."/edit/".$this->utility->encode($val->customer_id)."'><i class='glyphicon glyphicon-edit icon-white'></i></a>&nbsp;";
				}
				else
				{
					$permission = Data_model::get_permission($this->module_name);
					if($permission[0]->edit == 1)
					{
						$action = "<a title='Edit' class='btn bg-purple btn-flat btn-sm' href='".$this->controller."/edit/".$this->utility->encode($val->customer_id)."'><i class='glyphicon glyphicon-edit icon-white'></i></a>&nbsp;";
					}
				}

				$mobile = array();
				if($val->mobile != '')
					$mobile[] = $val->mobile;
				if($val->mobile_2 != '')
					$mobile[] = $val->mobile_2;
				if($val->mobile_3 != '')
					$mobile[] = $val->mobile_3;

				$email = array();
				if($val->email != '')
					$email[] = $val->email;
				if($val->email_2 != '')
					$email[] = $val->email_2;

				$data["data"][] = array(
					"customer" => $val->prefix.' '.$val->name,
					"address" => $val->address,
					"mobile" => implode('<br />',$mobile),
					"email" => implode('<br />',$email),
					"actions" => $action
				);
			}
		}
		echo json_encode($data);
	}
}
