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



class Employee extends Controller

{

	public $table="employee";

	public $primary_id="emp_id";

	public $foreign_table="role_master";

	public $foreign_id="role_id";

	public $msgName = "Employee";

	public $view = "employee";

	public $controller = "Employee";

	public $module_name = "employee";

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

			$data['add_permission'] =  $permission[0]->add;

			$data['edit_permission'] =  $permission[0]->edit;

			$data['print_permission'] =  $permission[0]->print;

			$data['delete_permission'] =  $permission[0]->delete;

		}



		$masterTable = $this->table;

		$parentTable = $this->foreign_table;

		$select = array($masterTable.'.*',$parentTable.'.role_name');

		$condition_1= $masterTable.'.'.$this->foreign_id;

		$condition_2= $parentTable.'.'.$this->foreign_id;

		$orderField = $masterTable.'.username';

		$orderType = 'ASC';

		$where = array($masterTable.'.delete_status' => 0);
		$where1 = array($masterTable.'.delete_status' => 1);

		/*$data['result'] = DB::table($this->table)

				->select($this->table.'.*',$this->foreign_table.'.zone_name','role_master.role_name')

				->leftJoin($this->foreign_table, $this->table.'.'.$this->primary_id, '=', $this->foreign_table.'.'.$this->foreign_id)

				->leftJoin('role_master', $this->table.'.role_id', '=', 'role_master.role_id')

				->where($masterTable.'.delete_status',"=",0)

				->orderBy($this->primary_id,'DESC')

				->get();*/

		$data['activeEmployee'] = Data_model::leftJoin($masterTable,$parentTable,$select,$condition_1,$condition_2,$where,$orderField ,$orderType);
		$data['deactiveEmployee'] = Data_model::leftJoin($masterTable,$parentTable,$select,$condition_1,$condition_2,$where1,$orderField ,$orderType);



		$data['zone'] = Data_model::retrive('zone_master','*',array('delete_status'=>0),'zone_name','ASC');



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

		$data['role_id'] = $this->role_id;

		$data['role'] = Data_model::retrive('role_master','*',array(array('role_id','<>','1')),'role_name','ASC');

		$data['zone'] = Data_model::retrive('zone_master','*',array('delete_status'=>0),'zone_name','ASC');

		$data['state'] = Data_model::retrive('state_master','*',array('delete_status'=>0),'state_name','ASC');

		$data['city'] = Data_model::retrive('city_master','*',array('delete_status'=>0),'city_name','ASC');

		return view($this->view.'/form',$data);

	}



	public function insert(Request $request)

	{

		$role_id = $request->input('role_id');

		$username = $request->input('username');

		$name  = $request->input('name');

		$mobile = $request->input('mobile');

		$password = md5($request->input('mobile'));

		$email = $request->input('email');

		$state = $request->input('state');

		if(!empty($request->input('zone_id')))

		{

			$zone_id = implode(',',$request->input('zone_id'));

		}

		else

		{

			$zone_id ='';

		}

		/*$state_id = $request->input('state_id');

		$city_id = $request->input('city_id');*/



		$data = array('role_id'=>$role_id,'username'=>$username,'password'=>$mobile,'name'=>$name,'mobile'=>$mobile,'email'=>$email,'zone_id'=>$zone_id);



		if($id = Data_model::store($this->table,$data))

		{

			Data_model::store('users',array('role_id'=>$role_id,'emp_id'=>$id,'username'=>$username,'mobile'=>$mobile,'email'=>$email,'password'=>$password));

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

		$data['role_id'] = $this->role_id;

		$data['result'] = Data_model::retrive($this->table,'*',array($this->primary_id=>$id),$this->primary_id);

		$data['role'] = Data_model::retrive('role_master','*',array(array('role_id','<>','1')),'role_name','ASC');

		$data['zone'] = Data_model::retrive('zone_master','*',array('delete_status'=>0),'zone_name','ASC');

		$data['state'] = Data_model::retrive('state_master','*',array('delete_status'=>0),'state_name','ASC');

		$data['city'] = Data_model::retrive('city_master','*',array('delete_status'=>0),'city_name','ASC');

		return view($this->view.'/form',$data);



	}



	public function update(Request $request)

	{



		$id = $request->input("id");

		$role_id = $request->input('role_id');

		$username = $request->input('username');

		$name  = $request->input('name');

		$mobile = $request->input('mobile');

		$password = md5($request->input('mobile'));

		$email = $request->input('email');

		$state = $request->input('state');

		if(!empty($request->input('zone_id')))

		{

			$zone_id = implode(',',$request->input('zone_id'));

		}

		else

		{

			$zone_id ='';

		}



		if($this->role_id == 1)

		{

			$pwd = md5($request->input('pwd'));

			$new_pwd = $request->input('pwd');



			$data = array('role_id'=>$role_id,'username'=>$username,'name'=>$name,'mobile'=>$mobile,'email'=>$email,'zone_id'=>$zone_id,'password'=>$new_pwd);



			Data_model::restore('users',array('password'=>$pwd),array('emp_id'=>$id));

		}

		else

		{

			$data = array('role_id'=>$role_id,'username'=>$username,'name'=>$name,'mobile'=>$mobile,'email'=>$email,'zone_id'=>$zone_id);

		}



		$where = array($this->primary_id=>$id);



		Data_model::restore($this->table,$data,$where);



		Data_model::restore('users',array('role_id'=>$role_id,'emp_id'=>$id,'username'=>$username,'mobile'=>$mobile,'email'=>$email),array('emp_id'=>$id));



		$msg = array('success' => $this->msgName.' Updated Sucessfully');





		return redirect($this->controller)->with($msg);

	}



	public function duplicate(Request $request)

	{

		$mobile = $request->input('name');

		$check = Data_model::retrive($this->table,'*',array('mobile'=>$mobile,'delete_status'=>0),$this->primary_id);



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

		$mobile = $request->input('name');

		$whereData = array(array('mobile', $mobile),array($this->primary_id, '<>', $id),'delete_status'=>0);

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

		$check = Data_model::retrive($this->table,'*',array($this->primary_id=>$id),$this->primary_id);

		if($check[0]->delete_status == 0)

		{

			$data = array('delete_status'=>1);

			$msg = $this->msgName.' Deactivate Sucessfully';

		}

		else

		{

			$data = array('delete_status'=>0);

			$msg = $this->msgName.' Activate Sucessfully';

		}

		$where = array($this->primary_id=>$id);

		Data_model::restore($this->table,$data,$where);

		$msg = array('success' => $msg);

		return redirect($this->controller)->with($msg);

	}

	public function change_emp_password(Request $request)
	{
		$emp_id = $request->input('emp_id');
		$emp_password = md5($request->input('emp_password'));
		$password = $request->input('emp_password');
		Data_model::restore('users',array('password'=>$emp_password),array('emp_id'=>$emp_id));
		Data_model::restore($this->table,array('password'=>$password),array('emp_id'=>$emp_id));
		$msg = array('success' => 'Password Changed Successfully');

		return redirect($this->controller)->with($msg);
	}

}

