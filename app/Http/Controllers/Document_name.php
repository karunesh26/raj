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
class Document_name extends Controller
{
	public $table="document_name_master";
	public $primary_id="id";
	public $field = "document_name";
	public $msgName = "Document Name";
	public $view = "document_name";
	public $controller = "Document_name";
	public $module_name = "document_name";
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
		
		$data['result']=Data_model::retrive($this->table,'*',array('delete_status'=>0),'document_name','ASC');
		
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
		$document_name  = trim(ucwords($request->input('document_name')));
	
		$data = array($this->field=>$document_name);
		
		if(Data_model::store($this->table,$data))
		{
			$msg = array('success' => $this->msgName.' Added Successfully');
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
		
		$id = $request->input("id");
		$document_name  = trim(ucwords($request->input('document_name')));
		
		$data = array($this->field=>$document_name);
		$where = array($this->primary_id=>$id);
		Data_model::restore($this->table,$data,$where);
		$msg = array('success' => $this->msgName.' Updated Successfully');
		return redirect($this->controller)->with($msg);
	}
	
	public function duplicate(Request $request)
	{
		$document_name  = trim(ucwords($request->input('document_name')));
		$whereData = array($this->field=>$document_name,'delete_status'=>0);
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
		$document_name  = trim(ucwords($request->input('document_name')));
		$whereData = array(array($this->field, $document_name),array($this->primary_id, '<>', $id),'delete_status'=>0);
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
		$msg = array('success' => $this->msgName.' Deleted Successfully');
		return redirect($this->controller)->with($msg);
	}
}
