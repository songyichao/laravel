<?php
namespace App\Http\Controllers;


use App\model\People;
use Illuminate\Http\Request;

class PeopleController extends Controller
{
	
	/**
	 * 列表页
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function index()
	{
		$data = (new People())->list();
		return view('people.index', ['list' => $data]);
	}
	
	/**
	 * 新建表单
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function create()
	{
		$people = new People();
		return view('people.create', ['people' => $people]);
	}
	
	/**
	 * 保存操作
	 * @param Request $request
	 * @return $this|\Illuminate\Http\RedirectResponse
	 */
	public function save(Request $request)
	{
		$data = $request->input('People');
		//		$this->validate($request, [
		//			'People.name' => 'required|min:2|max:10',
		//			'People.age' => 'required|integer|max:10',
		//			'People.sex' => 'required|integer|max:10',
		//		], [
		//			'required' => ':attribute 为必填项',
		//			'integer' => ':attribute 必须为数字',
		//			'max' => ':attribute 长度不符合要求',
		//			'min' => ':attribute 长度不符合要求',
		//		], [
		//			'People.name' => '姓名',
		//			'People.age' => '年龄',
		//			'People.sex' => '性别',
		//		]);
		//验证
		$validate = \Validator::make($request->input(), [
			'People.name' => 'required|min:2|max:10',
			'People.age' => 'required|integer',
			'People.sex' => 'required|integer',
		], [
			'required' => ':attribute 为必填项',
			'integer' => ':attribute 必须为数字',
			'max' => ':attribute 长度不符合要求',
			'min' => ':attribute 长度不符合要求',
		], [
			'People.name' => '姓名',
			'People.age' => '年龄',
			'People.sex' => '性别',
		]);
		if ($validate->fails()) {
			return redirect()->back()->withErrors($validate)->withInput();
		}
		$res = (new People())->createPeople($data);
		
		if ($res) {
			return redirect('people/index')->with('success', '添加成功');
		} else {
			return redirect()->back()->with('error', '添加失败');
		}
	}
	
	public function update(Request $request, $id)
	{
		$people = (new People())->getPeople($id);
		if ($request->isMethod('POST')) {
			$data = $request->input(['People']);
			if ((new People())->updatePeople($id, $data)) {
				return redirect('people/index')->with('success', '修改成功');
			} else {
				return redirect()->back()->with('error', '修改失败');
			}
		}
		return view('people.update', ['people' => $people]);
	}
	
	public function show($id)
	{
		$data = (new People())->getPeople($id);
		$data->sex = (new People())->sexList($data->sex);
		return view('people.show', ['data' => $data]);
	}
	
	public function delete($id)
	{
		if ((new People())->deletePeople($id)) {
			return redirect('people/index')->with('success', '删除成功');
		}
	}
}