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

class Quotation_transfer extends Controller
{
	public $table="quatation";
	public $primary_id="quatation_id";
	public $field = "quatation_no";
	public $foreign_table = "quatation_master";
	public $foreign_id = "inquiry_id";
	public $msgName = "Quotation Transfer";
	public $view = "quotation_transfer";
	public $controller = "Quotation_transfer";
	public $module_name = "quotation_transfer";
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
		$data['role_id'] = $this->role_id;
		$data['utility'] = $this->utility;

		$data['controller_name'] = $this->controller;
		$data['msgName']=$this->msgName;
		$data['primary_id']=$this->primary_id;
		$data['field']=$this->field;
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
		$data['field']=$this->field;
		$data['result'] = Data_model::retrive($this->table,'*',array('inquiry_id'=>$id),$this->primary_id);
		$data['zone'] = Data_model::retrive('zone_master','*',array('delete_status'=>0),'zone_name','ASC');
		return view($this->view.'/form',$data);
	}

	public function update(Request $request)
	{
		$id = $request->input("id");
		$zone_id  = $request->input('zone');

		$old_zone_id = Data_model::retrive($this->table,'*',array('inquiry_id'=>$id),'zone_id','ASC');
		$old_zone_id = $old_zone_id[0]->zone_id;

		Data_model::restore($this->table,array('zone_id'=>$zone_id),array('inquiry_id'=>$id));
		Data_model::restore('inquiry',array('project_zone'=>$zone_id),array('inquiry_id'=>$id));
		Data_model::restore('quatation_master',array('zone_id'=>$zone_id),array('inquiry_id'=>$id));

		$data = array('inquiry_id' => $id, 'old_zone_id' => $old_zone_id, 'transfer_zone_id' => $zone_id, 'user_id' => $this->user_id, 'datetime' => date('Y-m-d H:i:s'));
		Data_model::store('quotation_transfer_log',$data);
		$msg = array('success' => $this->msgName.' Sucessfully');

		return redirect($this->controller)->with($msg);
	}

	public function get_quotation_all(Request $request)
	{
		$data = array("data"=>"");

		$result = Data_model::db_query("SELECT `quatation`.`quatation_no`,`quatation`.`inquiry_id`,`zone_master`.`zone_name`,`inquiry`.`customer_id`,`customer_master`.`name`,`customer_master`.`prefix` FROM `quatation`
		INNER JOIN `zone_master` ON `zone_master`.`zone_id` = `quatation`.`zone_id`
		INNER JOIN `inquiry` ON `inquiry`.inquiry_id = `quatation`.`inquiry_id`
		INNER JOIN `customer_master` ON `customer_master`.customer_id = `inquiry`.`customer_id`");

		if($result)
		{
			foreach($result as $key=>$val)
			{
				if($this->role_id == 1)
				{
					$action = "<a title='Edit' class='btn bg-purple btn-flat btn-sm' href='".$this->controller."/edit/".$this->utility->encode($val->inquiry_id)."' class='btn btn-warning'><i class='glyphicon glyphicon-edit icon-white'></i></a>";
				}
				else
				{
					$permission = Data_model::get_permission($this->module_name);
					if($permission[0]->edit == 1)
					{
						$action = "<a title='Edit' class='btn bg-purple btn-flat btn-sm' href='".$this->controller."/edit/".$this->utility->encode($val->inquiry_id)."' class='btn btn-warning'><i class='glyphicon glyphicon-edit icon-white'></i></a>";
					}
				}

				$data["data"][] = array(
					"quotation_no" => $val->quatation_no,
					"customer_name" => $val->prefix.' '.$val->name,
					"zone_name" => $val->zone_name,
					"actions" => $action
				);
			}
		}
		echo json_encode($data);
	}
}
