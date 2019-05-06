<?php
namespace Base;

class User
{	
	public $type;
	public $inline_id;
	public $chat_id;
	public $username;
	public $name;
	public $message;
	public $message_id;
	public $action;
	public $selected;
	public $lmsg;


	//сохраняем все данные user в БД
	function __destruct(){
		DB::updateUser($this);
	}

}