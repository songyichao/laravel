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
	//	public function getStudent()
	//	{
	//		return DB::select('select * from student');
	//	}
	public function getStudent()
	{
		//		return DB::table('student')->get();
		//		return DB::table('student')
		//			->orderBy('id', 'desc')
		//			->first();
		return DB::table('student')
			->where([['id', 'in', '(12,13,14,4,5,6,7,8)']])
			->tosql();
			
		//		return DB::table('student')
		//			->whereRaw('id > ? and sex = ?', [2, 'nv'])
		//			->pluck('name');
		//5.3废弃了lists
		//		return DB::table('student')
		//			->whereRaw('id > ? and sex = ?', [2, 'nv'])
		//			->pluck('name', 'id');
		//		return DB::table('student')
		//			->select(['id', 'name', 'sex'])
		//			->whereRaw('id > ? and sex = ?', [2, 'nv'])
		//			->get();
		echo "<pre>";
		DB::table('student')
			->chunk(2, function ($student) {
				var_dump($student);
				return false;
			});
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
				'name' => $name,
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
	//	public function updateStudent($name, $age, $sex, $id)
	//	{
	//		return DB::update('update student set name = ? , age = ? , sex = ?
	// 			, utime = ? WHERE id = ?',
	//			[$name, intval($age), $sex, time(), intval($id)]);
	//	}
	public function updateStudent($name, $age, $sex, $id)
	{
		return DB::table('student')
			->where('id', $id)
			->update(
				[
					'name' => $name,
					'age' => $age,
					'sex' => $sex,
					'utime' => time()
				]
			);
	}
	
	//	public function deleteStudent($id)
	//	{
	//		return DB::delete('delete from student where id = ?',
	//			[$id]);
	//	}
	public function deleteStudent($id)
	{
		return DB::table('student')
			->where('id', $id)
			->delete();
	}
	
}
