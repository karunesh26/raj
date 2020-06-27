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

class User_rights extends Controller
{
	public $table="permission";
	public $primary_id="permission_id";
	public $msgName = "User Rights";
	public $view = "user_rights";
	public $controller = "User_rights";
	public $module_name = "user_rights";
	public $utility;

	public $role_session;
	public $user_id;

	public function __construct()
    {
		if (!Session::has('raj_user_id'))
		{
			$msg = array('error' => 'You Must First Login To Access');
			Redirect::to('/')->send()->with($msg);
		}

		$this->role_session = Session::get('raj_role_id');
		$this->user_id = Session::get('raj_user_id');

		if($this->role_session != '1')
		{
			$permission = Data_model::get_permission($this->module_name);
			if(empty($permission))
			{
				Redirect::to('/')->send();
			}
			else
			{
				$add_per = $permission[0]->add;
				if($add_per != '1')
				{
					Redirect::to('/')->send();
				}
			}
		}

		date_default_timezone_set("Asia/Kolkata");
		$this->utility = new Utility();
	}

	public function index()
	{
		$data['utility'] = $this->utility;
		$data['role']=Data_model::db_query("select * from `role_master` where role_id != '1' Order By role_name ASC ");
		$data['controller_name'] = $this->controller;
		$data['msgName']=$this->msgName;
		$data['primary_id']=$this->primary_id;
		return view($this->view.'/manage',$data);
	}
	public function get_user(Request $request)
	{
		$role_id = $request->input('role_id');
		$user = Data_model::retrive('users','*',array('role_id'=>$role_id),'role_id');

		echo '<option  value="">Select</option>';
		foreach($user as $key=> $val)
		{
			echo '<option value="'.$val->id.'" >'.$val->username.'</option>';
		}
	}
	public function get_data(Request $request)
	{
		$role_id = $request->input('role_id');
		$user_id = $request->input('user_id');

		$data['module'] = Data_model::db_query("Select * from module ORDER BY display_menu DESC, display_sequence ASC");

		$data['role_id'] = $role_id;
		$data['user_id'] = $user_id;
		$data['action'] = 'update_data';
		$data['table'] = $this->table;
		$data['controller'] = $this->controller;

		return view($this->view.'/get_data',$data);
	}

	public function update_data(Request $request)
	{
		$role_id = $request->input('role_id');
		$user_id = $request->input('user_id');
		$module_id = $request->input('module_id');
		$view  = $request->input('view');
		$add  = $request->input('add');
		$edit  = $request->input('edit');
		$delete  = $request->input('delete');
		$active  = $request->input('active');
		$search  = $request->input('search');
		$print  = $request->input('print');

		for($i =0 ;$i<count($module_id); $i++)
		{
			if(isset($view[$module_id[$i]]))
			{
				$view_flg = 1;
			}
			else
			{
			   $view_flg = 0;
			}
			if(isset($add[$module_id[$i]]))
			{
				$add_flg = 1;
			}
			else
			{
				$add_flg = 0;
			}
			if(isset($edit[$module_id[$i]]))
			{
				$edit_flg = 1;
			}
			else
			{
				$edit_flg = 0;
			}
			if(isset($delete[$module_id[$i]]))
			{
				$delete_flg = 1;
			}
			else
			{
				$delete_flg = 0;
			}

			if(isset($active[$module_id[$i]]))
			{
				$active_flg = 1;
			}
			else
			{
				$active_flg = 0;
			}

			$check = Data_model::retrive($this->table,'*',array('user_id'=>$user_id,'role_id'=>$role_id,'module_id'=>$module_id[$i]),'permission_id');

			if(count($check) == 0)
			{
				$data = array('role_id'=>$role_id,'user_id'=>$user_id,'module_id'=>$module_id[$i],'add'=>$add_flg,'edit'=>$edit_flg,'delete'=>$delete_flg,'active'=>$active_flg,'search'=>'1','print'=>'1','view'=>$view_flg,'added_by'=>$this->user_id,'added_time'=>date("Y-m-d H:i:s"));

				Data_model::store($this->table,$data);
			}
			else
			{
				$data = array('role_id'=>$role_id,'user_id'=>$user_id,'module_id'=>$module_id[$i],'add'=>$add_flg,'edit'=>$edit_flg,'delete'=>$delete_flg,'active'=>$active_flg,'search'=>'1','print'=>'1','view'=>$view_flg,'updated_by'=>$this->user_id);

				$where=array($this->primary_id=>$check[0]->permission_id);

				Data_model::restore($this->table,$data,$where);
			}
		}


		$msg = array('success' => $this->msgName.' Updated Sucessfully');
		return redirect($this->controller)->with($msg);
	}

}
