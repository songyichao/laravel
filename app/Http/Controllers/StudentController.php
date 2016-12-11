<?php
/**
 * Created by PhpStorm.
 * User: MIOJI
 * Date: 2016/12/11
 * Time: 下午9:02
 */

namespace App\Http\Controllers;


use App\model\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Debug\Dumper;

class StudentController extends Controller
{
	public function list()
	{
		$ladybug = new Dumper();
		$ladybug->dump((new Student())->getStudent());
	}
	
	public function add(Request $request, $name, $age, $sex)
	{
		$ladybug = new Dumper();
		//		$ladybug->dump($request);
		$ladybug->dump((new Student())->addStudent($name, $age, $sex));
	}
	
	public function update(Request $request, $name, $age, $sex, $id)
	{
		$ladybug = new Dumper();
		$ladybug->dump((new Student())->updateStudent($name, $age, $sex, $id));
	}
	
	public function delete(Request $request, $id)
	{
		$ladybug = new Dumper();
		$ladybug->dump((new Student())->deleteStudent($id));
	}
}