<?php 
namespace Base;

/**
 * 
 */
class TG
{	
	public $reciever;
	public $api;
	public $user;

	function __construct($token, &$user)
	{
		$this->user = $user;
		$this->api = "https://api.telegram.org/bot" . $token;
		$this->reciever = json_decode(file_get_contents('php://input'), TRUE);
		$this->preloader($user);
		$this->updateInfo($user);	
	}

	//подтягиваем данные из БД и передаем в $user
	public function preloader(User &$user){	
		if (DB::takeInfo($this->reciever['message']['chat']['id'])) {
			$user = DB::takeInfo($this->reciever['message']['chat']['id']);
		}elseif(DB::takeInfo($this->reciever['callback_query']['message']['chat']['id'])){
			$user = DB::takeInfo($this->reciever['callback_query']['message']['chat']['id']);
		}
		unset($user->type); 
		unset($user->message);
		unset($user->message_id);
	}

	//установка данных(из вебхука) для user
	public function updateInfo(User &$user){
		$callback = $this->reciever['callback_query'];
		$message = $this->reciever['message'];
		if ($callback) {
			$user->type = 'inline';
		    $user->message = $callback['data'];
		    $user->inline_id = $callback['id'];
		    $user->chat_id = $callback['message']['chat']['id'];
		    $user->username = $callback['message']['chat']['username'];
		    $user->name = $callback['message']['chat']['first_name'];
		    $user->message_id = $callback['message']['message_id'];
		}elseif ($message) {
			$user->type = 'message';
			$user->chat_id = $message['chat']['id'];
			$user->message = $message['text'];
			$user->username = $message['chat']['username'];
			$user->name = $message['chat']['first_name'];
			$user->message_id = $message['message_id'];
		}
		//проверяем и добавляем юзера если его там нет
		$userInfo = DB::getById('oop_users', $user->chat_id);
		if (empty($userInfo)) {
			DB::saveUser($user);
		}
	}


	public function sendMess(User $user, $text, $keybord = null) {
		$rslt = json_decode(file_get_contents($this->api . '/sendMessage?chat_id=' . $user->chat_id . '&parse_mode=html&text=' . urlencode($text)  . '&reply_markup=' . $keybord), true);
		//обновляем last_message_id у user
		$user->lmsg = $rslt['result']['message_id'];
		return $rslt;
	}

	public function editMess(User $user, $text, $keybord = null){
		file_get_contents($this->api . '/editMessageText?chat_id='. $user->chat_id . '&message_id=' . $user->lmsg . '&parse_mode=html&text=' . urlencode($text) . '&reply_markup=' . $keybord);
	}
}

