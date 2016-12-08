<?php
/**
 * Created by PhpStorm.
 * User: MIOJI
 * Date: 2016/11/11
 * Time: 下午12:34
 */

namespace App\Http\Controllers;

//use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Debug\Dumper;

class AdminController extends Controller
{
	public function user(Request $request, $id)
	{
		$ladybug = new Dumper();
		$ladybug->dump($request->method());
		$ladybug->dump($id);
		$ladybug->dump($request->input('name'));
		$cookie = cookie('name', 'songyi123cho', 1);
		$request->flash();
		$ladybug->dump($request->old());
		return response('Hello word')->cookie($cookie);
	}
	
	public function set(Request $request)
	{
		$request->session()->put('username', 'songyichao');
	}
	
	public function get(Request $request)
	{
		$ladybug = new Dumper();
		$val = $request->session()->get('username');
		$ladybug->dump($val);
	}
	
	public function add()
	{
		return view('admin.add');
	}
	
	public function save(Request $request)
	{
		$this->validate($request, [
			'username' => 'required|min:5|max:225',
			'password' => 'required|min:6|max:225',
		]);
	}
}