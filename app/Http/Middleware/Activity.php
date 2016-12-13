<?php
namespace App\Http\Middleware;

use Closure;

class Activity
{
	//前置
//	public function handle($request, Closure $next)
//	{
//		if (time() < strtotime('2016-12-1')) {
//			return redirect('activity0');
//		}
//
//		return $next($request);
//	}
	//后置
	/**
	 * @param $request
	 * @param Closure $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		
		$response = $next($request);
		return $response;
		echo 'haha';
	}
}