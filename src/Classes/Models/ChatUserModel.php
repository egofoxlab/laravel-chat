<?php
/**
 * Developed by EGOFOXLAB.
 * Site http://egofoxlab.com/
 * Copyright (c) 2019.
 */

namespace Egofoxlab\Chat\Classes\Models;

use Carbon\Carbon;
use Egofoxlab\Chat\Classes\Struct\ChatUserRowStruct;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Egofoxlab\Chat\Classes\Providers\Util as EgoUtil;

/**
 * Class ChatUserModel
 *
 * @property $table
 * @property $id
 * @property $created_at
 * @property $updated_at
 * @package App\Models
 */
class ChatUserModel extends Model {

	protected $table = 'chat_user';

	/**
	 * Create Chat User
	 *
	 * @param ChatUserRowStruct $row
	 * @return int
	 */
	public function create(ChatUserRowStruct $row) {
		return DB::table($this->table)->insertGetId([
			'cu_chat_id' => $row->getChatId(),
			'cu_user_id' => $row->getUserId(),
			'cu_last_view_date' => Carbon::now(),
			'cu_date_create' => Carbon::now(),
			'cu_date_update' => Carbon::now()
		]);
	}

	/**
	 * Update Last view chat date
	 *
	 * @param int $chatId
	 * @param int $userId
	 * @return bool
	 */
	public function updateLastViewDate(int $chatId, int $userId) {
		$result = DB::table($this->table)
			->where([
				'cu_chat_id' => $chatId,
				'cu_user_id' => $userId
			])
			->update([
				'cu_last_view_date' => Carbon::now()
			]);

		return $result > 0;
	}

	/**
	 * Return chat users
	 *
	 * @param int $chatId
	 * @return array
	 */
	public function getChatUsers(int $chatId) {
		$sql = "
			SELECT
			
				GROUP_CONCAT(cu.cu_user_id SEPARATOR ',') AS user_list
			
			FROM chat_user cu
			WHERE 1
				  AND cu.cu_chat_id = {$chatId}
			";

		$data = DB::select($sql);
		$data = empty($data) ? [] : (array)$data[0];

		if (empty($data)) {
			return $data;
		}

		$data = explode(',', $data['user_list']);

		foreach ($data as &$item) {
			$item = intval($item);
		}

		return $data;
	}

}
