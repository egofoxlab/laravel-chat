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
		$sql = [
			"
			CREATE TABLE IF NOT EXISTS chat
				(
					c_id          bigint UNSIGNED AUTO_INCREMENT COMMENT 'Row ID'
						PRIMARY KEY,
					c_code        varchar(255) NULL COMMENT 'Chat code. Use for additional identify.',
					c_name        varchar(255) NOT NULL COMMENT 'Chat Uniq name',
					c_date_create datetime     NOT NULL COMMENT 'Creation Date',
					c_date_update datetime     NOT NULL COMMENT 'Date update',
					CONSTRAINT `CHAT_c_name`
						UNIQUE (c_name)
				)
					COMMENT 'Chat table'
			",
			"
			CREATE TABLE IF NOT EXISTS chat_message
				(
					cm_id          bigint UNSIGNED AUTO_INCREMENT COMMENT 'Row ID'
						PRIMARY KEY,
					cm_chat_id     bigint UNSIGNED NOT NULL COMMENT 'Chat ID',
					cm_user_id     bigint UNSIGNED NOT NULL COMMENT 'Send from User ID',
					cm_message     text            NOT NULL COMMENT 'Message',
					cm_date_create datetime        NOT NULL COMMENT 'Creation date',
					cm_date_update datetime        NOT NULL COMMENT 'Date update',
					CONSTRAINT FK_chat_messages_chat
						FOREIGN KEY (cm_chat_id) REFERENCES chat (c_id)
							ON UPDATE CASCADE ON DELETE CASCADE
				)
					COMMENT 'Chat message list'
			",
			"
			CREATE TABLE IF NOT EXISTS chat_user
				(
					cu_id             bigint UNSIGNED AUTO_INCREMENT COMMENT 'Row ID'
						PRIMARY KEY,
					cu_chat_id        bigint UNSIGNED NOT NULL COMMENT 'Chat ID',
					cu_user_id        bigint UNSIGNED NOT NULL COMMENT 'User ID',
					cu_last_view_date datetime        NOT NULL COMMENT 'Last view chat by user',
					cu_date_create    datetime        NOT NULL COMMENT 'Creation date',
					cu_date_update    datetime        NOT NULL COMMENT 'Date update',
					CONSTRAINT FK_chat_users_chat
						FOREIGN KEY (cu_chat_id) REFERENCES chat (c_id)
							ON UPDATE CASCADE ON DELETE CASCADE
				)
					COMMENT 'Chat user list'
			"
		];
		foreach ($sql as $subsql) {
			DB::statement($subsql);
		}
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::disableForeignKeyConstraints();
		Schema::dropIfExists('chat');
		Schema::dropIfExists('chat_message');
		Schema::dropIfExists('chat_user');
		Schema::enableForeignKeyConstraints();
	}
}
