<?php
/**
 * Created by PhpStorm.
 * User: MIOJI
 * Date: 2016/12/12
 * Time: 上午10:12
 */

namespace App\Http\Controllers;


use App\model\Teacher;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
	public function list()
	{
		$list = (new Teacher())->list();
		return view('teacher.list', ['list' => $list]);
//	 	dd($list->created_at);
	}
	 
	public function add(Request $request, $name, $age, $class)
	{
		dd((new Teacher())->add($name, $age, $class));
	}
	
	public function update(Request $request, $name, $age, $class, $id)
	{
		dd((new Teacher())->updateTeacher($name, $age, $class, $id));
	}
	
	public function delete(Request $request, $id)
	{
		dd((new Teacher())->deleteTeacher($id));
	}
	
}