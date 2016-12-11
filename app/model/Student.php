<?php
namespace App\model;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Debug\Dumper;
use Illuminate\Support\Facades\DB;

class Student extends Model
{
	
	/**
	 * 查找学生
	 * @return mixed
	 */
	public function getStudent()
	{
		return DB::select('select * from student');
	}
	
	/**
	 * 添加学生
	 * @param $name
	 * @param $age
	 * @param $sex
	 * @return mixed
	 */
//	public function addStudent($name, $age, $sex)
//	{
//		return DB::insert('insert into student (name, age, sex, ctime, utime) VALUE
//			(?, ?, ?, ?, ?)', [$name, $age, $sex, time(), time()]);
//	}
	public function addStudent($name, $age, $sex)
	{
//		return DB::table('student')->insert(
//			[
//				'name'=> $name,
//				'age' => $age,
//				'sex' => $sex,
//				'ctime' => time(),
//				'utime' => time(),
//			]
//		);
		return DB::table('student')->insertGetId(
			[
				'name'=> $name,
				'age' => $age,
				'sex' => $sex,
				'ctime' => time(),
				'utime' => time(),
			]
		);
	}
	
	/**
	 * 更新
	 * @param $name
	 * @param $age
	 * @param $sex
	 * @param $id
	 * @return mixed
	 */
	public function updateStudent($name, $age, $sex, $id)
	{
		return DB::update('update student set name = ? , age = ? , sex = ?
 			, utime = ? WHERE id = ?',
			[$name, intval($age), $sex, time(), intval($id)]);
	}
	
	public function deleteStudent($id)
	{
		return DB::delete('delete from student where id = ?',
			[$id]);
	}
	
}
