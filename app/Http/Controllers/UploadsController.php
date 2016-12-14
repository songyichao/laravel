<?php

namespace App\Http\Controllers;

use Log;
use Illuminate\Http\Request;
use Mail;
use Cache;
use Illuminate\Support\Facades\Storage;

class UploadsController extends Controller
{
	public function error()
	{
		Log::info('这是一个info级别的日志');
//		Log::warning('这是一个warning级别的日志');
//		Log::info('这是一个info级别的日志');
	}
	
	public function cache1()
	{
//		Cache::put('key1', 'val1', 10);
//		dd(Cache::add('key1', 'val1', 10));
//		dd(Cache::forever('key3','val3'));
		if (Cache::has('key1')){
			dd(Cache::get('key1'));
		}
	}
	
	public function cache2()
	{
//		dd(Cache::get('key3'));
//		dd(Cache::pull('key3'));
		dd(Cache::forget('key1'));
	}
	
	public function mail()
	{
		//		Mail::raw('邮件内容', function ($message) {
		//			$message->from('songyichao74@163.com', 'syc');
		//			$message->subject('测试邮件');
		//			$message->to('songyichao@mioji.com');
		//		});
		Mail::send('upload.mail',
			['name' => 'word'],
			function ($message) {
				$message->to('songyichao@mioji.com');
			});
	}
	
	//
	public function upload(Request $request)
	{
		if ($request->isMethod('POST')) {
			$file = $request->file('file');
			if ($file->isValid()) {
				$original_name = $file->getClientOriginalName();
				$extension = $file->getClientOriginalExtension();
				$type = $file->getClientMimeType();
				//临时绝对目录
				$real_path = $file->getRealPath();
				$file_name = time() . '_' . uniqid() . '.' . $extension;
				$res = Storage::disk('uploads')->put($file_name, file_get_contents($real_path));
				dd($res);
			}
			
		}
		return view('upload.upload');
	}
}
