<?php
/**
 * Developed by EGOFOXLAB.
 * Site http://egofoxlab.com/
 * Copyright (c) 2019.
 */

namespace Egofoxlab\LaravelChat\Classes\Models;

use Carbon\Carbon;
use Egofoxlab\LaravelChat\Classes\Struct\ChatMessageRowStruct;
use Egofoxlab\LaravelChat\Classes\Struct\ChatRowStruct;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Egofoxlab\LaravelChat\Classes\Providers\Util as EgoUtil;

/**
 * Class ChatMessageModel
 *
 * @property $table
 * @property $id
 * @property $created_at
 * @property $updated_at
 * @package App\Models
 */
class ChatMessageModel extends Model {

	protected $table = 'chat_message';

	/**
	 * Create Message
	 *
	 * @param ChatMessageRowStruct $row
	 * @return int
	 */
	public function create(ChatMessageRowStruct $row) {
		return DB::table($this->table)->insertGetId([
			'cm_chat_id' => $row->getChatId(),
			'cm_user_id' => $row->getuserId(),
			'cm_message' => $row->getMessage(),
			'cm_date_create' => Carbon::now(),
			'cm_date_update' => Carbon::now()
		]);
	}

	/**
	 * Get one
	 *
	 * @param int $id
	 * @param bool $isStruct
	 * @return array|ChatRowStruct|null|object
	 */
	public function getOne(int $id, bool $isStruct = false) {
		$sql = "
			SELECT
			
				cm.cm_id          AS id,
				cm.cm_chat_id     AS chat_id,
				cm.cm_user_id     AS user_id,
				cm.cm_message     AS message,
				cm.cm_date_create AS date_create,
				cm.cm_date_update AS date_update
			
			FROM chat_message cm
			WHERE 1
				  AND cm.cm_id = {$id}
			";

		$data = DB::select($sql);
		$data = empty($data) ? [] : (array)$data[0];

		if (empty($data)) {
			return $data;
		}

		if ($isStruct) {
			$result = (new ChatMessageRowStruct())
				->setId(intval($data['id']))
				->setChatId(intval($data['chat_id']))
				->setUserId(intval($data['user_id']))
				->setMessage($data['message'])
				->setDateCreate($data['date_create'])
				->setDateUpdate($data['date_update']);

			return $result;
		}

		return $data;
	}

	/**
	 * Get chat message list
	 *
	 * @param int $chatId
	 * @param int|null $lastMessageId
	 * @param bool $isStruct
	 * @return array|ChatMessageRowStruct[]|null|object
	 */
	public function getList(int $chatId, int $lastMessageId = null, bool $isStruct = false) {
		$where = '';

		if ($lastMessageId > 0) {
			$where .= "AND cm.cm_id > {$lastMessageId}";
		}

		$sql = "
			SELECT
			
				cm.cm_id          AS id,
				cm.cm_chat_id     AS chat_id,
				cm.cm_user_id     AS user_id,
				cm.cm_message     AS message,
				cm.cm_date_create AS date_create,
				cm.cm_date_update AS date_update
			
			FROM chat_message cm
			WHERE 1
				  AND cm.cm_chat_id = {$chatId}
				  {$where}
			";

		$data = DB::select($sql);
		$data = empty($data) ? [] : $data;

		if (empty($data)) {
			return $data;
		}

		if ($isStruct) {
			/** @var ChatMessageRowStruct[] $result */
			$result = [];

			foreach ($data as $item) {
				$item = (array)$item;
				$result[] = (new ChatMessageRowStruct())
					->setId(intval($item['id']))
					->setChatId(intval($item['chat_id']))
					->setUserId(intval($item['user_id']))
					->setMessage($item['message'])
					->setDateCreate($item['date_create'])
					->setDateUpdate($item['date_update']);
			}

			return $result;
		}

		foreach ($data as &$item) {
			$item = (array)$item;
		}

		return $data;
	}

	/**
	 * Get count of unread chat messages for some user.
	 *
	 * @param int $chatId
	 * @param int $userId
	 * @return array|bool|int|null|object
	 */
	public function getUnreadCount(int $chatId, int $userId) {
		$sql = "
			SELECT COUNT(*) AS count
			FROM chat_message cm
			
			WHERE 1
				  AND cm.cm_chat_id = {$chatId}
				  AND cm.cm_date_create >
					  (
						  SELECT cu.cu_last_view_date
						  FROM chat_user cu
						  WHERE 1
								AND cu.cu_chat_id = {$chatId}
								AND cu.cu_user_id = {$userId}
					  )
			";

		$data = DB::select($sql);
		$data = empty($data) ? false : (array)$data[0];

		if (empty($data)) {
			return $data;
		}

		return intval($data['count']);
	}

	/**
	 * Is read messages from chat
	 *
	 * @param int $chatId
	 * @param int $userId
	 * @return array|bool|null|object
	 */
	public function isRead(int $chatId, int $userId) {
		return $this->getUnreadCount($chatId, $userId) > 0;
	}

	/**
	 * Get count messages of chat
	 *
	 * @param int $chatId
	 * @return array|bool|int|null|object
	 */
	public function getCountChatMessages(int $chatId) {
		$sql = "
			SELECT COUNT(*) AS count
			FROM chat_message cm
			
			WHERE 1
				  AND cm.cm_chat_id = {$chatId}
			";

		$data = DB::select($sql);
		$data = empty($data) ? false : (array)$data[0];

		if (empty($data)) {
			return $data;
		}

		return intval($data['count']);
	}

	/**
	 * Return last message
	 *
	 * @param int $chatId
	 * @param bool|null $isStruct
	 * @return ChatMessageRowStruct|array|null
	 */
	public function getLastMessage(int $chatId, bool $isStruct = null) {
		$sql = "
			SELECT
			
				cm.cm_id          AS id,
				cm.cm_chat_id     AS chat_id,
				cm.cm_user_id     AS user_id,
				cm.cm_message     AS message,
				cm.cm_date_create AS date_create,
				cm.cm_date_update AS date_update
			
			FROM chat_message cm
			
			WHERE 1
				  AND cm.cm_chat_id = {$chatId}
			
			ORDER BY cm.cm_id DESC
			
			LIMIT 1
			";

		$data = DB::select($sql);
		$data = empty($data) ? null : (array)$data[0];

		if (empty($data)) {
			return null;
		}

		if ($isStruct) {
			return $this->toStruct($data);
		}

		return $data;
	}

	/**
	 * Convert to row struct
	 *
	 * @param $data
	 * @return ChatMessageRowStruct
	 */
	public function toStruct($data) {
		$data = (array)$data;

		return (new ChatMessageRowStruct())
			->setId((int)EgoUtil::getArrItem($data, 'id'))
			->setChatId((int)EgoUtil::getArrItem($data, 'chat_id'))
			->setUserId((int)EgoUtil::getArrItem($data, 'user_id'))
			->setMessage(EgoUtil::getArrItem($data, 'message'))
			->setDateCreate(EgoUtil::getArrItem($data, 'date_create'))
			->setDateUpdate(EgoUtil::getArrItem($data, 'date_update'));
	}

}
