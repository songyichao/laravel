<?php
namespace app\Http\Controllers;


use Illuminate\Http\Request;

class CollectionController
{
	/**
	 * Illuminate\Support\Collection 类提供一个流畅、便利的封装来操控数组数据。
	 * 如下面的示例代码，我们用 collect 函数从数组中创建新的集合实例，对每一个元素运行 strtoupper 函数，然后移除所有的空元素：
	 */
	public function create()
	{
		$collection = collect(['syc', 'xuz', null])->map(function ($name) {
			return strtoupper($name);
		})->reject(function ($name) {
			return empty($name);
		});
	}
	
	/**
	 * 这里打印的结果
	 *
	 * Collection {#258 ▼
	 *   #items: array:3 [▼
	 *      1111 => "2332"
	 *      "jaaj" => "2323"
	 *      "www" => "2323"
	 *       ]
	 *   }
	 * @param Request $request
	 * 文档上有一句这样的话，"默认 Eloquent 模型的查询结果总是以 Collection 实例返回。"
	 */
	public function collection1(Request $request)
	{
		$collection = collect($request->all());
		
		dd($collection);
	}
	
	/**
	 * @internal param Request $request
	 */
	public function collection2()
	{
		$collection = collect(['1' => 23, 'lis' => 'what', 'liuhd' => 'wuwuwu', 'www' => 'http']);
		dump($collection);
		//all() 返回集合的所代表的底层{数组}
		dump($collection->all());
		//avg() 返回集合中所有项目的平均值
		dump($collection->avg());
	}
}