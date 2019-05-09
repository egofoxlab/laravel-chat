<?php
/**
 * Developed by EGOFOXLAB.
 * Site http://egofoxlab.com/
 * Copyright (c) 2019.
 */

namespace Egofoxlab\Chat\Classes\Providers;

use DB;
use Egofoxlab\Chat\Classes\Models\ChatMessageModel;
use Egofoxlab\Chat\Classes\Models\ChatModel;
use Egofoxlab\Chat\Classes\Models\ChatUserModel;
use Egofoxlab\Chat\Classes\Struct\ChatMessageRowStruct;
use Egofoxlab\Chat\Classes\Struct\ChatRowStruct;
use Egofoxlab\Chat\Classes\Struct\ChatUserRowStruct;
use Egofoxlab\Chat\Classes\Providers\Util as EgoUtil;
use http\Exception\RuntimeException;

class ChatProvider {

	/**
	 * Create chat
	 *
	 * @param ChatRowStruct $chatRow - Chat row struct with data
	 * @param int[] $users - IDs of users
	 * @return array|ChatRowStruct|object|null
	 * @throws \Exception
	 */
	public function createChat(ChatrowStruct $chatRow, array $users) {
		$chat = null;

		try {
			DB::beginTransaction();

			//region Define Models
			$chatModel = new ChatModel();
			$chatUserModel = new ChatUserModel();
			//endregion

			//  Create Chat
			$chatId = $chatModel->create($chatRow);
			$chat = $chatModel->getOne($chatId, true);

			//  Create Chat Users
			foreach ($users as $user) {
				$user = (int)$user;

				//  Check user ID
				if ($user <= 0) {
					throw new \InvalidArgumentException('Invalid user ID in users array.');
				}

				$chatUserRow = (new ChatUserRowStruct())
					->setChatId($chat->getId())
					->setUserId($user);

				if (!($chatUserModel->create($chatUserRow) > 0)) {
					throw new \RuntimeException("Error occurred while create chat user.");
				}
			}

			DB::commit();
		} catch (\Exception $ex) {
			DB::rollBack();

			throw new \Exception($ex->getMessage());
		}

		return $chat;
	}

	/**
	 * Return chats in which the user participated.
	 *
	 * @param $user
	 * @return array
	 * @throws \InvalidArgumentException
	 */
	public function getChatListByUser($user) {
		//  User ID
		$user = (int)$user;

		if ($user <= 0) {
			throw new \InvalidArgumentException('Invalid user ID.');
		}

		//region Define Models
		$chatModel = new ChatModel();
		$chatUserModel = new ChatUserModel();
		$chatMessageModel = new ChatMessageModel();
		//endregion

		$list = $chatModel->getListByUser($user, true);
		$list = empty($list) ? [] : $list;

		foreach ($list as $chat) {
			$item = [
				'chat' => $chat->toArray(),
				'thumbnail' => null,
				'userList' => [],
				'lastMessage' => null,
			];

			//  @todo: Add call event listeners BEFORE and AFTER

			//	Users in chat
			foreach ($chatUserModel->getChatUsers($chat->getId()) as $userId) {
				//	Unread count
				$user['unreadCount'] = $chatMessageModel->getUnreadCount($chat->getId(), $userId);

				$item['userList'][] = $user;
			}

			//	Last message
			$lastMessage = $chatMessageModel->getLastMessage($chat->getId());

			if (!empty($lastMessage)) {
				$item['lastMessage'] = $lastMessage;
			}

			$data[] = $item;
		}

		usort($data, function ($a, $b) use ($user) {
			foreach ($a['userList'] as $item) {
				if ((int)$item['user_id'] === $user && (int)$item['unreadCount'] > 0) {

					return -1;

					break;
				}
			}

			return 1;
		});

		return $data;
	}

	/**
	 * Add new message to chat
	 *
	 * @param int $chatId - Chat ID
	 * @param int $userId - Sender(user) ID
	 * @param string $message - Chat message
	 * @throws \Exception
	 */
	public function newMessage($chatId, $userId, $message) {
		//  Chat ID
		$chatId = (int)$chatId;

		if ($chatId <= 0) {
			throw new \InvalidArgumentException('Invalid chat ID.');
		}

		//  User ID
		$userId = (int)$userId;

		if ($chatId <= 0) {
			throw new \InvalidArgumentException('Invalid user ID.');
		}

		//  Message
		if (!is_string($message)) {
			throw new \InvalidArgumentException('Message must be a string.');
		}

		$message = trim($message);

		if (empty($message)) {
			throw new \InvalidArgumentException('Message is empty.');
		}

		try {
			DB::beginTransaction();

			//region Define Models
			$chatMessageModel = new ChatMessageModel();
			$chatUserModel = new ChatUserModel();
			//endregion

			$chatMessageRow = (new ChatMessageRowStruct())
				->setChatId($chatId)
				->setUserId($userId)
				->setMessage($message);

			//  Add message
			$lastMessageId = $chatMessageModel->create($chatMessageRow);

			if (!($lastMessageId > 0)) {
				throw new RuntimeException('Error occurred while add message.');
			}

			//  Update last view date chat by this user
			$chatUserModel->updateLastViewDate($chatId, $userId);

			DB::commit();
		} catch (\Exception $ex) {
			DB::rollBack();

			throw new \RuntimeException($ex->getMessage());
		}
	}

	/**
	 * Add user to chat
	 *
	 * @param int $chatId - Chat ID
	 * @param int $userId - User ID
	 * @throws \InvalidArgumentException
	 * @throws \RuntimeException
	 */
	public function addUserToChat($chatId, $userId) {
		//  Chat ID
		$chatId = (int)$chatId;

		if ($chatId <= 0) {
			throw new \InvalidArgumentException('Invalid chat ID.');
		}

		//  User ID
		$userId = (int)$userId;

		if ($userId <= 0) {
			throw new \InvalidArgumentException('Invalid user ID.');
		}

		//region Define Models
		$chatUserModel = new ChatUserModel();
		//endregion

		$result = (int)$chatUserModel->create(
			(new ChatUserRowStruct())
				->setChatId($chatId)
				->setUserId($userId)
		);

		if ($result <= 0) {
			throw new \RuntimeException('Error occurred while add user to chat.');
		}
	}

	/**
	 * Load message
	 * @todo: Add limit FROM, TO. Behaviour like in Skype
	 *
	 * @param int $chatId - Chat ID
	 * @return array|ChatMessageRowStruct[]|object|null
	 * @throws \InvalidArgumentException
	 */
	public function loadMessages($chatId) {
		//  Chat ID
		$chatId = (int)$chatId;

		if ($chatId <= 0) {
			throw new \InvalidArgumentException('Invalid chat ID.');
		}

		//region Define Models
		$chatMessageModel = new ChatMessageModel();
		//endregion

		$messages = $chatMessageModel->getList($chatId, null, true);

		if (empty($messages)) {
			return [];
		}

		return $messages;
	}

}
