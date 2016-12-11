<?php
namespace App\model;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
	public static function getUser()
	{
		return 'syc';
	}
}