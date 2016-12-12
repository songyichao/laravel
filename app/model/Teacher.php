<?php
/**
 * Created by PhpStorm.
 * User: MIOJI
 * Date: 2016/12/12
 * Time: 上午10:12
 */

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
	//指定表名
	protected $table = 'teacher';
	
	//指定主键
	protected $primaryKey = 'id';
	
	//自动维护时间
	public $timestamps = true;
	
	//允许批量赋值的字段
	protected $fillable = ['name', 'age', 'class', 'ctime', 'utime', 'created_at', 'updated_at'];
	
	//不允许批量赋值的字段
	protected $guarded = [];
	
	//时间格式
	protected function getDateFormat()
	{
		return time();
	}
	
	
	/**
	 * 格式化输出的时间
	 * @param mixed $val
	 * @return false|string
	 */
	protected function asDateTime($val)
	{
		return $val;
//		return date('Y-m-d H:i:s', $val);
	}
	
	public function list()
	{
		//		return Teacher::all();
//		return Teacher::find(3);
		//		return Teacher::findOrFail(11);
				return Teacher::get();
		//		return Teacher::where('id', '>=', '1')->get();
	}
	
	public function add($name, $age, $class)
	{
		//		$teacher = new Teacher();
		//		$teacher->name = $name;
		//		$teacher->age = $age;
		//		$teacher->class = $class;
		//		$teacher->ctime = time();
		//		$teacher->utime = time();
		//		return $teacher->save();
//		return Teacher::create(
//			[
//				'name' => $name,
//				'age' => $age,
//				'class' => $class,
//				'ctime' => time(),
//				'utime' => time(),
//			]
//		);
		//以属性查找实例，若没有添加
//		return Teacher::firstOrCreate(
//			[
//				'name' => $name,
//				'age' => $age,
//				'class' => $class,
//				'ctime' => time(),
//				'utime' => time(),
//			]
//		);
		//以属性查找实例，若没有生成实例
		$teacher =  Teacher::firstOrNew(
			[
				'name' => $name,
				'age' => $age,
				'class' => $class,
//				'ctime' => time(),
//				'utime' => time(),
			]
		);
		return $teacher->save();
	}
	
	/**
	 * @param $name
	 * @param $age
	 * @param $class
	 * @param $id
	 * @return mixed
	 */
	public function updateTeacher($name, $age, $class, $id)
	{
//		$teacher = Teacher::find($id);
//		$teacher->name = $name;
//		$teacher->age = $age;
//		$teacher->class = $class;
//		return $teacher->save();
		return Teacher::where('id', '>=', '1')
			->update(['age' => $age]);
	}
	
	public function deleteTeacher($id)
	{
		//模型删除
//		$teacher = Teacher::find($id);
//		return $teacher->delete();
		//主键删除
//		return Teacher::destroy($id);
		//删除指定条件的数据
		return Teacher::where('id', $id)->delete();
	}
}