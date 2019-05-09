<?php
/**
 * Developed by EGOFOXLAB.
 * Site http://egofoxlab.com/
 * Copyright (c) 2019.
 */

namespace Egofoxlab\LaravelChat\Classes\Struct;

class ChatRowStruct extends BaseStruct {

	/**
	 * Row ID
	 *
	 * @var int
	 */
	private $id;

	/**
	 * Chat code. Use for additional identify.
	 *
	 * @var string
	 */
	private $code;

	/**
	 * Chat name
	 *
	 * @var string
	 */
	private $name;

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
	 * @return ChatRowStruct
	 */
	public function setId($id): self {
		$this->id = $id;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getCode() {
		return $this->code;
	}

	/**
	 * @param string $code
	 * @return $this
	 */
	public function setCode($code) {
		$this->code = $code;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param string $name
	 * @return ChatRowStruct
	 */
	public function setName($name): self {
		$this->name = $name;

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
	 * @return ChatRowStruct
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
	 * @return ChatRowStruct
	 */
	public function setDateUpdate($dateUpdate): self {
		$this->dateUpdate = $dateUpdate;

		return $this;
	}

}
