<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
	use Notifiable;
	
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name', 'email', 'password',
	];
	
	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password', 'remember_token',
	];
	
	public function ll()
	{
		$table->addRow();
		$table->addCell('1134', $cell_row_span)->addText('D412.11周五', $font_style_12, $paragraph_style);
		$cell = $table->addCell('8505', $cell_col_span);
		$cell->addTextBreak();
		$cell->addText('qqwewqwqweeeqqqw', $font_style_12, $paragraph_style_2);
		$cell->addText('asdjsadjasdasd',
			$font_style_12,
			$paragraph_style_2);
		$cell->addTextBreak();
		$cell->addText('cccccwwww',
			$font_style_12,
			$paragraph_style_2);
		$cell->addText('lslslslslslqqqsskkks',
			$font_style_12,
			$paragraph_style_2);
		$cell->addText('tototototo',
			$font_style_12,
			$paragraph_style_2);
	}
}
