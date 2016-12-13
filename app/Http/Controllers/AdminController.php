<?php
/**
 * Created by PhpStorm.
 * User: MIOJI
 * Date: 2016/11/11
 * Time: 下午12:34
 */

namespace App\Http\Controllers;

//use App\Http\Controllers\Controller;
use App\model\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Debug\Dumper;
use Illuminate\Support\Facades\Session;

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
	
	public function showuser()
	{
		return Admin::getUser();
	}
	
	public function request1(Request $request)
	{
		//		dd($request->input('name'));
		//		dd($request->input('sex', 'no'));
		//		dd($request->all());
		echo $request->method();
		if ($request->isMethod('GET')) {
			echo 'yes';
		} else {
			echo 'no';
		}
		
		var_dump($request->ajax());
		var_dump($request->is('admin/*'));
		echo "<br>";
		var_dump($request->url());
		
	}
	
	/**
	 * session
	 * @param Request $request
	 */
	public function session1(Request $request)
	{
		$request->session()->put('key1', 'value1');
		session()->put('key2', 'value2');
		Session::put('key3', 'value3');
		Session::put(['key4' => 'value4']);
		Session::push('student', 'syc');
		Session::push('student', 'sy');
	}
	
	/**
	 * session
	 * @param Request $request
	 */
	public function session2(Request $request)
	{
		return Session::get('mess','no');
		echo $request->session()->get('key1');
		echo session()->get('key2');
		echo Session::get('key3');
		echo Session::get('key4', 'default');
		var_dump(Session::pull('student'));
		//		dd(Session::all());
		//		if (Session::has('key1')) {
		//			dd(Session::all());
		//		} else {
		//			echo 'no';
		//		}
		Session::forget('key1');
		
		Session::flush();
		dd(Session::all());
		//暂存数据
		Session::flash('key-flash', 'val-flash');
		
	}
	
	public function response()
	{
		//		json
		//		$data = [
		//			'error_id' => 1,
		//			'error_str' => '错误',
		//			'data' => 'syc',
		//		];
		//
		//		return response()->json($data);
		//重定向
		//		return redirect('admin/session2')->with('mess', 'super');
		//action
//		return redirect()->action('AdminController@session2')->with('mess', 'who');
		//route()
//		return redirect()->route('session2')->with('mess', 'who');
		return redirect()->back();
	}
	
	public function activity0()
	{
		return '活动即将开始';
	}
	
	public function activity1()
	{
		return '活动进行中，恭喜中奖';
	}
	
	public function activity2()
	{
		return '活动进行中，谢谢参与';
	}
}