# EGOFOXLAB Chat

**Laravel Chat** is simple chat from box for quick start create 
your own chat.


## Demo version how it can work

![](http://nfs.egofoxlab.com/laravel-chat/demo.gif)


## What in box? 

This package provide:

* Laravel command for start chat from CLI
* Opportunity for extending base functions
* Migration with for DB for saving all your messages
* Create more than two users in chat
* Build on socket library [cboden/ratchet](https://packagist.org/packages/cboden/ratchet)

## How it use?

1. Install package `composer require egofoxlab/laravel-chat` in root folder.
2. Run migration `php artisan migrate` for create chat tables.
3. Create your chat command `php artisan make:command MyChat` it's your first 
    step for overwrite chat.
4. In new created *MyChat* class you can set your configurations.
5. Run chat `php artisan chat_server:serve` - default command name for chat. You
    can overwrite it.

When you create your own chat command and extend it from `laravel-chat` it's
start point for extending your chat with your requirements.

## Where code demo?

Also I created separated repository with example where you can see chat in working
with frontend. [REPO](https://github.com/egofoxlab/chat)
 
