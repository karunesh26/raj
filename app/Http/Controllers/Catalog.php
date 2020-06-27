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

class Catalog extends Controller
{
	public $table="catalog_master";
	public $primary_id="id";
	public $field = "catalog_title";
	public $msgName = "Catalog";
	public $view = "catalog";
	public $controller = "Catalog";
	public $module_name = "catalog";
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
		$data['result']=Data_model::retrive($this->table,'*',array('delete_status'=>0),'catalog_title','ASC');
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
		$title  = $request->input('title');
		$files = $request->file('file');
		
		$i=0;
		foreach($files as $file)
		{
			$file_ext = $file->getClientOriginalExtension();
			$path = 'external/catalog/';
			if(!is_dir($path))
			{
				 mkdir($path, 0777, TRUE);
			}
			$t = time()+$i;
			$document_file_nm= $t.'.'.$file_ext;
			$file->move($path,$document_file_nm);
			
			$data = array($this->field=>$title[$i],'catalog_file'=>$document_file_nm);
			Data_model::store($this->table,$data);
			$i++;
		}
		
		$msg = array('success' => $this->msgName.' Added Sucessfully');
	
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
		$title  = $request->input('title');
		$old_file  = $request->input('old_file');
		
		if($request->file('file'))
		{
			$file = $request->file('file');
			$file_ext = $file->getClientOriginalExtension();
			$path = 'external/catalog/';
			$unlink_path = 'external/catalog/'.$old_file;
			if(!is_dir($path))
			{
				mkdir($path, 0777, TRUE);
			}
			$t = time();
			$document_file_nm= $t.'.'.$file_ext;
			$file->move($path,$document_file_nm);
			unlink($unlink_path);
		}
		else
		{
			$document_file_nm = $old_file;
		}
		
		$data = array($this->field=>$title,'catalog_file'=>$document_file_nm);
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
}
