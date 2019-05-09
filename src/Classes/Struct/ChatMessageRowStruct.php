<?php
/**
 * Developed by EGOFOXLAB.
 * Site http://egofoxlab.com/
 * Copyright (c) 2019.
 */

namespace Egofoxlab\LaravelChat\Classes\Struct;

class ChatMessageRowStruct extends BaseStruct {

	/**
	 * Row ID
	 * @var int
	 */
	private $id;

	/**
	 * Chat ID
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
	 * Message
	 *
	 * @var string
	 */
	private $message;

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
	 * @return ChatMessageRowStruct
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
	 * @return ChatMessageRowStruct
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
	 * @return ChatMessageRowStruct
	 */
	public function setUserId($userId): self {
		$this->userId = $userId;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getMessage() {
		return $this->message;
	}

	/**
	 * @param string $message
	 * @return ChatMessageRowStruct
	 */
	public function setMessage($message): self {
		$this->message = $message;

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
	 * @return ChatMessageRowStruct
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
	 * @return ChatMessageRowStruct
	 */
	public function setDateUpdate($dateUpdate): self {
		$this->dateUpdate = $dateUpdate;

		return $this;
	}

}
