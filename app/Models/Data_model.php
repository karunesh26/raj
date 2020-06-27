<?php

namespace App;
namespace App\models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Session;

class Data_model extends Model
{
	
	public static  function db_query($query)
    {
        return DB::select($query);
	}
	public static function db_update($query)
	{
		return DB::update($query);
	}
	
	public static  function retrive($table,$fields,$where,$orderField,$orderType='desc')
    {
        return DB::table($table)->select($fields)->where($where)->orderBy($orderField, $orderType)->get();
		
    }
	public  static function store($table, $data)
	{
		
			return DB::table($table)->insertGetId($data); 
	}
	
	public static function restore($table,$data,$where)
	{
		return DB::table($table)->where($where)->update($data);
		
	}
	
	public static function remove($table,$where)
	{
		 return  DB::table($table)->where($where)->delete();
	}
	
	public  static function singleJoin($masterTable,$parentTable,$select,$condition_1,$condition_2,$where,$orderField,$orderType='desc')
	{
		return DB::table($masterTable)
            ->join($parentTable, $condition_1, '=',$condition_2)
			->select($select)
			->where($where)
			->orderBy($orderField, $orderType)
            ->get();
	}

	public  static function singleJoin_raw($masterTable,$parentTable,$select,$condition_1,$condition_2,$where,$orderField,$orderType='desc')
	{
		return  DB::table($masterTable)
            ->join($parentTable, $condition_1, '=',$condition_2)
			->select($select)
			->whereRaw($where)
			->orderBy($orderField, $orderType)
			->get();
		
	}
	
	public  static function leftJoin($masterTable,$parentTable,$select,$condition_1,$condition_2,$where,$orderField,$orderType='desc')
	{
		return DB::table($masterTable)
            ->leftJoin($parentTable, $condition_1, '=',$condition_2)
			->select($select)
			->where($where)
			->orderBy($orderField, $orderType)
            ->get();
	}
	public static function get_menu($disp_name)
	{
		$permission_table = 'permission';
		$role_session = Session::get('raj_role_id');
		$user_id = Session::get('raj_user_id');
		
		if($role_session == 1)
		{
			$where = array('display_menu'=>$disp_name);
			return DB::table('module')->select('*')->where($where)->orderBy('display_sequence','asc')->get();
		}
		else
		{
			return DB::select("select ".$permission_table.".*, module.* from ".$permission_table." inner join module on module.module_id = ".$permission_table.".module_id where  ".$permission_table.".role_id = '".$role_session."' and ".$permission_table.".user_id = '".$user_id."' and module.display_menu = '".$disp_name."' and  ".$permission_table.".view = '1' ORDER BY display_sequence");
		}
	}
	
	 public static function get_permission($module_name)
	{
		$role_session = Session::get('raj_role_id');
		$user_id = Session::get('raj_user_id');
		
		return DB::select("select permission.*, module.* from permission inner join module on module.module_id = permission.module_id where permission.role_id = '".$role_session."' and permission.user_id = '".$user_id."' and module.name = '".$module_name."' and  permission.view = '1' ");
	} 
}
?>