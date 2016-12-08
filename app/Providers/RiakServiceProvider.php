<?php

namespace App\Providers;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Connection;
use Illuminate\Support\ServiceProvider;

class RiakServiceProvider extends ServiceProvider
{
	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot(ResponseFactory $response)
	{
		$response->macro('caps', function () {
		});
	}
	
	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
		//
		$this->app->singleton(Connection::class, function ($app) {
			return new Connection(config('mysql'));
		});
	}
}
