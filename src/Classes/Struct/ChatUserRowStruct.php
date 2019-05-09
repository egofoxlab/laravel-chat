<?php
/**
 * Developed by EGOFOXLAB.
 * Site http://egofoxlab.com/
 * Copyright (c) 2019.
 */

namespace Egofoxlab\Chat\Classes\Struct;

class ChatUserRowStruct extends BaseStruct {

	/**
	 * Row ID
	 *
	 * @var int
	 */
	private $id;

	/**
	 * Chat ID
	 *
	 * @var int
	 */
	private $chatId;

	/**
	 * User ID
	 *
	 * @var int
	 */
	private $userId;

	/**
	 * Date create
	 *
	 * @var string
	 */
	private $dateCreate;

	/**
	 * Date update
	 *
	 * @var string
	 */
	private $dateUpdate;

	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param int $id
	 * @return ChatUserRowStruct
	 */
	public function setId($id): self {
		$this->id = $id;

		return $this;
	}

	/**
	 * @return int
	 */
	public function getChatId() {
		return $this->chatId;
	}

	/**
	 * @param int $chatId
	 * @return ChatUserRowStruct
	 */
	public function setChatId($chatId): self {
		$this->chatId = $chatId;

		return $this;
	}

	/**
	 * @return int
	 */
	public function getUserId() {
		return $this->userId;
	}

	/**
	 * @param int $userId
	 * @return ChatUserRowStruct
	 */
	public function setUserId($userId): self {
		$this->userId = $userId;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getDateCreate() {
		return $this->dateCreate;
	}

	/**
	 * @param string $dateCreate
	 * @return ChatUserRowStruct
	 */
	public function setDateCreate($dateCreate): self {
		$this->dateCreate = $dateCreate;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getDateUpdate() {
		return $this->dateUpdate;
	}

	/**
	 * @param string $dateUpdate
	 * @return ChatUserRowStruct
	 */
	public function setDateUpdate($dateUpdate): self {
		$this->dateUpdate = $dateUpdate;

		return $this;
	}

}
