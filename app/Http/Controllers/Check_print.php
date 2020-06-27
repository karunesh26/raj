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
use PDF;

class Check_print extends Controller
{
	public $table="check_print";
	public $primary_id="id";
	public $field = "amount";
	public $msgName = "Check Print";
	public $view = "check_print";
	public $controller = "Check_print";
	public $module_name = "check_print";
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
		$data['party'] = Data_model::retrive('party_master','*',array('delete_status'=>0),'party_name','ASC');
		$data['msgName']=$this->msgName;
		$data['primary_id']=$this->primary_id;
		$data['field']=$this->field;
		return view($this->view.'/form',$data);
	}

	public function insert(Request $request)
	{
		$date = date('Y-m-d');
		$added_date = date('Y-m-d H:i:s');
		$party_id  = $request->input('party_id');
		$amount = $request->input('amount');
		$check_date = date('d-m-Y',strtotime($request->input('date')));
		$date = date('Y-m-d',strtotime($request->input('date')));

		$data = array('party_id'=>$party_id,'date'=>$date,'amount'=>$amount,'added_by'=>$this->user_id,'added_date'=>$added_date);

		if(Data_model::store($this->table,$data))
		{
			/* Party Detail */
			$party = Data_model::retrive('party_master','*',array('party_id'=>$party_id),'party_name','ASC');
			$party_name = $party[0]->account_name;
			$cdate = explode("-",$check_date);
			$cdate = implode("",$cdate);
			$data['check_date'] = $cdate;
			$data['amount'] = $amount;
			$data['party_name'] = $party_name;
			$pdf = PDF::loadView($this->view.'/check_print',$data);
			$pdf->setPaper([0, 0,263.622,578.2677], 'landscape');
			return $pdf->stream('Check '.date('d-m-Y H:i:s'));
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
	public function get_check_detail(Request $request)
	{
		$data = array("data"=>"");
		$result = Data_model::db_query("select `".$this->table."`.*,`party_master`.party_name,`users`.username from `".$this->table."` INNER JOIN `party_master` ON `party_master`.party_id = `".$this->table."`.party_id INNER JOIN `users` ON `users`.id = `".$this->table."`.added_by where `".$this->table."`.delete_status = 0 ");

		if($result)
		{
			foreach($result as $key=>$val)
			{
				if($this->role_id == 1)
				{
					$action = "<a class='btn bg-maroon btn-flat btn-sm confirm-delete' href='".$this->controller."/delete/".$this->utility->encode($val->party_id)."'><i class='glyphicon glyphicon-trash icon-white'></i></a>";
				}
				else
				{
					$permission = Data_model::get_permission($this->module_name);
					if($permission[0]->delete == 1)
					{
						$action = "<a class='btn bg-maroon btn-flat btn-sm confirm-delete'  href ='".$this->controller."/delete/".$this->utility->encode($val->party_id)."'><i class='glyphicon glyphicon-trash icon-white'></i></a>";
					}
				}
				$data["data"][] = array(
					"party_name" => $val->party_name,
					"date" => date('d-m-Y',strtotime($val->date)),
					"amount" => $val->amount,
					"added_by" => $val->username,
					"actions" => $action
				);
			}
		}
		echo json_encode($data);
	}
}
