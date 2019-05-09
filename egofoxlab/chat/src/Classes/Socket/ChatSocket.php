<?php

namespace Egofoxlab\Chat\Classes\Socket;

use Egofoxlab\Chat\Classes\Models\ChatModel;
use Egofoxlab\Chat\Classes\Models\ChatUserModel;
use Egofoxlab\Chat\Classes\Providers\ChatProvider;
use Egofoxlab\Chat\Classes\Socket\Base\BaseSocket;
use Egofoxlab\Chat\Classes\Struct\ChatRowStruct;
use Log;
use Ratchet\ConnectionInterface;
use Egofoxlab\Chat\Classes\Providers\Util as EgoUtil;

class ChatSocket extends BaseSocket {

	protected $clientList;

	public function __construct() {
		$this->clientList = new \SplObjectStorage();
	}

	function onOpen(ConnectionInterface $conn) {
		$this->clientList->attach($conn);

		//  Create new chat
		$chatModel = new ChatModel();
		$chatProvider = new ChatProvider();
		//  Get chat by code
		$chat = $chatModel->getOneByCode(md5('1'), true);
		//  Old messages
		$oldMessages = [];

		//  Create chat if not exists
		if (empty($chat)) {
			$chat = $chatProvider->createChat(
				(new ChatRowStruct())
					->setCode(md5('1'))
					->setName('chat_name_' . time()),
				//  Mock users
				[]
			);
		}

		if (!empty($chat)) {
			//  Load old messages
			foreach ($chatProvider->loadMessages($chat->getId()) as $message) {
				$oldMessages[] = [
					'userInfo' => [
						'id' => $message->getUserId(),
						'name' => 'User ' . $message->getUserId(),
						'avatar' => null
					],
					'data' => [
						'text' => $message->getMessage()
					]
				];
			}
		}

		$conn->send(json_encode([
			'type' => 'system',
			'userInfo' => [
				'id' => null,
				'name' => 'Administrator',
				'avatar' => null
			],
			'data' => [
				'text' => 'Welcome to EGO Chat demo!',
				'oldMessages' => $oldMessages
			]
		]));

		foreach ($this->clientList as $item) {
			$item->send("Greetings!\r\n
				Welcome to EGOCHAT!
				");
		}
	}

	function onClose(ConnectionInterface $conn) {
		$this->clientList->detach($conn);

		echo "Detach client. Current count: {$this->clientList->count()}" . PHP_EOL;

		foreach ($this->clientList as $item) {
			/** @var ConnectionInterface $client */
			$client = $item;

			$client->send("Someone detach.");
		}
	}

	function onError(ConnectionInterface $conn, \Exception $e) {
		echo "In client error occurred.";

		$conn->close();
	}

	function onMessage(ConnectionInterface $from, $msg) {
		$messageData = json_decode($msg, true);

		//  Do something with message for example check empty message, filter message etc.
		if (empty($messageData)) {
			return;
		}

		$userId = (int)EgoUtil::getArrItem($messageData, 'userInfo.id');

		if ($userId > 0) {
			$chatModel = new ChatModel();
			$chatUserModel = new ChatUserModel();
			$chatProvider = new ChatProvider();
			$chat = $chatModel->getOneByCode(md5(EgoUtil::getArrItem($messageData, 'data.chatId')), true);
			$chatUsers = $chatUserModel->getChatUsers($chat->getId());

			//  Add user to chat if not exist yet
			if (!in_array($userId, $chatUsers)) {
				$chatProvider->addUserToChat($chat->getId(), $userId);
			}

			//  Save message in DB
			$chatProvider->newMessage($chat->getId(), $userId, EgoUtil::getArrItem($messageData, 'data.text'));
		}

		foreach ($this->clientList as $item) {
			/** @var ConnectionInterface $client */
			$client = $item;

			//$client->send("Hey! Message from someone! `{$msg}`");
			$client->send($msg);
		}
	}

}
