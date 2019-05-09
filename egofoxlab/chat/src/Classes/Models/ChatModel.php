<?php
/**
 * Developed by EGOFOXLAB.
 * Site http://egofoxlab.com/
 * Copyright (c) 2019.
 */

namespace Egofoxlab\Chat\Classes\Models;

use Carbon\Carbon;
use Egofoxlab\Chat\Classes\Struct\ChatRowStruct;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Egofoxlab\Chat\Classes\Providers\Util as EgoUtil;

/**
 * Class ChatModel
 *
 * @property $table
 * @property $id
 * @property $created_at
 * @property $updated_at
 * @package App\Models
 */
class ChatModel extends Model {

	protected $table = 'chat';

	/**
	 * Return list by User ID
	 *
	 * @param int $userId
	 * @param bool|null $isStruct
	 * @return ChatRowStruct[]|array|null
	 */
	public function getListByUser(int $userId, bool $isStruct = null) {
		$sql = "
			SELECT
			
				c.c_id          AS id,
		        c.c_code		AS code,
				c.c_name        AS name,
				c.c_date_create AS date_create,
				c.c_date_update AS date_update
			
			FROM chat c
				LEFT JOIN chat_user cu ON cu.cu_chat_id = c.c_id
			WHERE 1
				  AND cu.cu_user_id = {$userId}
			";

		$data = DB::select($sql);
		$data = empty($data) ? null : (array)$data;

		if (empty($data)) {
			return null;
		}

		if ($isStruct) {
			/** @var ChatRowStruct[] $result */
			$result = [];

			foreach ($data as $item) {
				$result[] = $this->toStruct($item);
			}

			return $result;
		}

		foreach ($data as &$item) {
			$item = (array)$item;
		}

		return $data;
	}

	/**
	 * Create Chat
	 *
	 * @param ChatRowStruct $row
	 * @return int
	 */
	public function create(ChatRowStruct $row) {
		return DB::table($this->table)->insertGetId([
			'c_code' => $row->getCode(),
			'c_name' => $row->getName(),
			'c_date_create' => Carbon::now(),
			'c_date_update' => Carbon::now()
		]);
	}

	/**
	 * Return Chat by ID
	 *
	 * @param int $id
	 * @param bool $isStruct
	 * @return array|ChatRowStruct|null|object
	 */
	public function getOne(int $id, bool $isStruct = false) {
		$sql = "
			SELECT
			
				c.c_id          AS id,
		        c.c_code		AS code,
				c.c_name        AS name,
				c.c_date_create AS date_create,
				c.c_date_update AS date_update
			
			FROM chat c
			WHERE 1
				  AND c.c_id = '{$id}'
			";

		$data = DB::select($sql);
		$data = empty($data) ? null : (array)$data[0];

		if (empty($data)) {
			return $data;
		}

		if ($isStruct) {
			$result = $this->toStruct($data);

			return $result;
		}

		return $data;
	}

	/**
	 * Return chat by code
	 *
	 * @param string $code
	 * @param bool $isStruct
	 * @return array|ChatRowStruct
	 */
	public function getOneByCode(string $code, bool $isStruct = false) {
		$sql = "
			SELECT
			
				c.c_id          AS id,
		     	c.c_code		AS code,
				c.c_name        AS name,
				c.c_date_create AS date_create,
				c.c_date_update AS date_update
			
			FROM chat c
			WHERE 1
				  AND c.c_code = '{$code}'
			";

		$data = DB::select($sql);
		$data = empty($data) ? [] : (array)$data[0];

		if (empty($data)) {
			return $data;
		}

		if ($isStruct) {
			$result = $this->toStruct($data);

			return $result;
		}

		return $data;
	}

	/**
	 * Return chat by name
	 *
	 * @param string $name
	 * @param bool $isStruct
	 * @return array|ChatRowStruct|null|object
	 */
	public function getOneByName(string $name, bool $isStruct = false) {
		$sql = "
			SELECT
			
				c.c_id          AS id,
		     	c.c_code		AS code,
				c.c_name        AS name,
				c.c_date_create AS date_create,
				c.c_date_update AS date_update
			
			FROM chat c
			WHERE 1
				  AND c.c_name = '{$name}'
			";

		$data = DB::select($sql);
		$data = empty($data) ? null : (array)$data[0];

		if (empty($data)) {
			return $data;
		}

		if ($isStruct) {
			$result = $this->toStruct($data);

			return $result;
		}

		return $data;
	}

	/**
	 * Convert row to struct
	 *
	 * @param $data
	 * @return ChatRowStruct
	 */
	public function toStruct($data) {
		$data = (array)$data;

		return (new ChatRowStruct())
			->setId((int)EgoUtil::getArrItem($data, 'id'))
			->setCode(EgoUtil::getArrItem($data, 'code'))
			->setName(EgoUtil::getArrItem($data, 'name'))
			->setDateCreate(EgoUtil::getArrItem($data, 'data_create'))
			->setDateUpdate(EgoUtil::getArrItem($data, 'date_update'));
	}

}
