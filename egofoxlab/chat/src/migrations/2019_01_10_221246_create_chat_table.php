<?php
/**
 * Developed by EGOFOXLAB.
 * Site http://egofoxlab.com/
 * Copyright (c) 2019.
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChatTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('tasks', function (Blueprint $table) {
			$sql = "
				CREATE TABLE IF NOT EXISTS `chat` (
					`c_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Row ID',
					`c_name` varchar(255) NOT NULL COMMENT 'Chat Uniq name',
					`c_date_create` datetime NOT NULL COMMENT 'Creation Date',
					`c_date_update` datetime NOT NULL COMMENT 'Date update',
					PRIMARY KEY (`c_id`),
					UNIQUE KEY `Индекс 2` (`c_name`)
				) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='Chat table';
				";

			DB::statement($sql);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('chat');
	}
}
