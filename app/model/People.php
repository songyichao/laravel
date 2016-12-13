<?php
namespace App\model;

use Illuminate\Database\Eloquent\Model;

class People extends Model
{

	const SEX_UN = 0;

	const SEX_BOY = 1;

	const SEX_GIRL = 2;

	protected $table = 'people';
	
	protected $primaryKey = 'id';
	
	public $timestamps = true;
	
	//允许批量赋值的字段
	protected $fillable = ['name', 'age', 'sex', 'created_at', 'updated_at'];
	
	//不允许批量赋值的字段
	protected $guarded = [];
	
	//时间格式
	protected function getDateFormat()
	{
		return time();
	}
	
	/**
	 * 处理性别
	 * @param null $ind
	 * @return array|mixed
	 */
	public function sexList($ind = null)
	{
		$arr = [
			self::SEX_UN => '未知',
			self::SEX_BOY => '男',
			self::SEX_GIRL => '女',
		];
		if ($ind !== null) {
			return array_key_exists($ind, $arr) ? $arr[$ind] : $arr[self::SEX_UN];
		}
		return $arr;
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
	
	/**
	 * 分页取出数据
	 * @return mixed
	 */
	public function list()
	{
		$people = People::paginate(20);
		foreach ($people as &$person) {
			$person->sex = $this->sexList($person->sex);
		}
		return $people;
	}
	
	/**
	 * 新建数据
	 * @param $data
	 * @return bool
	 */
	public function createPeople($data)
	{
		$people = new People();
		$people->name = $data['name'];
		$people->age = $data['age'];
		$people->sex = $data['sex'];
		return $people->save();
	}
	
	/**
	 * 获取单个people
	 * @param $id
	 * @return mixed
	 */
	public function getPeople($id)
	{
		return People::find($id);
	}
	
	/**
	 * @param $data
	 * @return mixed
	 */
	public function updatePeople($id, $data)
	{
		return People::where('id', $id)
			->update($data);
	}
	
	/**
	 * delete people
	 * @param $id
	 * @return mixed
	 */
	public function deletePeople($id)
	{
		return People::where('id', $id)->delete();
	}
}