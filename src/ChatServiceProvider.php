<?php
/**
 * Developed by EGOFOXLAB.
 * Site http://egofoxlab.com/
 * Copyright (c) 2019.
 */

namespace Egofoxlab\LaravelChat;

use Egofoxlab\LaravelChat\Commands\ChatServer;
use Illuminate\Support\ServiceProvider;

class ChatServiceProvider extends ServiceProvider {
	/**
	 * Bootstrap services.
	 *
	 * @return void
	 */
	public function boot() {
		$this->loadMigrationsFrom(__DIR__ . '/migrations');
		$this->loadRoutesFrom(__DIR__ . '/routes.php');
		$this->publishes([
			//__DIR__ . '/views' => resource_path('/views/vendor/ego_chat'),
		]);
		$this->loadViewsFrom(__DIR__ . '/views', 'ego_chat');
		$this->commands([
			ChatServer::class
		]);
	}

	/**
	 * Register services.
	 *
	 * @return void
	 */
	public function register() {
		$this->app->make('Egofoxlab\LaravelChat\ChatController');
	}
}
