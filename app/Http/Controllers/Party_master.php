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

class Party_master extends Controller
{
	public $table="party_master";
	public $primary_id="party_id";
	public $field = "party_name";
	public $msgName = "Party";
	public $view = "party_master";
	public $controller = "Party_master";
	public $module_name = "party_master";
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
		$date = date('Y-m-d');
		$added_date = date('Y-m-d H:i:s');
		$party_name  = trim(ucwords($request->input('party_name')));
		$mobile_no = $request->input('mobile_no');
		$email = $request->input('email');
		$company_name  = $request->input('company_name');
		$bank_name  = $request->input('bank_name');
		$account_name  = $request->input('account_name');
		$ifsc_code  = $request->input('ifsc_code');
		$address  = $request->input('address');

		$data = array($this->field=>$party_name,'mobile_no'=>$mobile_no,'email'=>$email,'company_name'=>$company_name,'bank_name'=>$bank_name,'account_name'=>$account_name,'ifsc_code'=>$ifsc_code,'address'=>$address,'date'=>$date,'added_by'=>$this->user_id,'added_date'=>$added_date);

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
		$updated_date = date('Y-m-d H:i:s');
		$id = $request->input("id");
		$party_name  = trim(ucwords($request->input('party_name')));
		$mobile_no = $request->input('mobile_no');
		$email = $request->input('email');
		$company_name  = $request->input('company_name');
		$bank_name  = $request->input('bank_name');
		$account_name  = $request->input('account_name');
		$ifsc_code  = $request->input('ifsc_code');
		$address  = $request->input('address');

		$data = array($this->field=>$party_name,'mobile_no'=>$mobile_no,'email'=>$email,'company_name'=>$company_name,'bank_name'=>$bank_name,'account_name'=>$account_name,'ifsc_code'=>$ifsc_code,'address'=>$address,'updated_by'=>$this->user_id,'updated_date'=>$updated_date);
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
	public function get_all_party(Request $request)
	{
		$data = array("data"=>"");
		$result = Data_model::retrive($this->table,'*',array('delete_status'=>0),'party_name','ASC');

		if($result)
		{
			foreach($result as $key=>$val)
			{
				if($this->role_id == 1)
				{
					$action = "<a title='Edit' class='btn bg-purple btn-flat btn-sm ' href='".$this->controller."/edit/".$this->utility->encode($val->party_id)."'><i class='glyphicon glyphicon-edit icon-white'></i></a>&nbsp;";

					$action .= "<a class='btn bg-maroon btn-flat btn-sm confirm-delete' href='".$this->controller."/delete/".$this->utility->encode($val->party_id)."'><i class='glyphicon glyphicon-trash icon-white'></i></a>";
				}
				else
				{
					$permission = Data_model::get_permission($this->module_name);
					if($permission[0]->edit == 1)
					{
						$action = "<a title='Edit' class='btn bg-purple btn-flat btn-sm' href='".$this->controller."/edit/".$this->utility->encode($val->party_id)."'><i class='glyphicon glyphicon-edit icon-white'></i></a>&nbsp;";
					}
					if($permission[0]->delete == 1)
					{
						$action .= "<a class='btn bg-maroon btn-flat btn-sm confirm-delete'  href ='".$this->controller."/delete/".$this->utility->encode($val->party_id)."'><i class='glyphicon glyphicon-trash icon-white'></i></a>";
					}
				}
				$data["data"][] = array(
					"party_name" => $val->party_name,
					"mobile_no" => $val->mobile_no,
					"bank_name" => $val->bank_name,
					"account_name" => $val->account_name,
					"address" => $val->address,
					"actions" => $action
				);
			}
		}
		echo json_encode($data);
	}
}
