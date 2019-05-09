<?php

namespace Egofoxlab\LaravelChat;

use Illuminate\Routing\Controller;

class ChatController extends Controller {

	public function index() {
		return view('ego_chat::index');
	}

}
