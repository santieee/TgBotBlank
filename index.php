<?php 
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
require_once "vendor/autoload.php";
Use Base\DB;
Use Base\User;
Use Base\TG;
Use Base\Logger;

$log = new Logger;
$user = new User;
$tg = new TG('****TOKEN*****', $user);

if ($user->message == (strtolower('Hi') || strtolower('Hello') || strtolower('Привет') || strtolower('Хай'))) {
	$array_elmnt[] = array(array("text"=>"🌐 Главное меню","callback_data"=>'/start')); 
	$keyboard_pr = array("inline_keyboard"=>$array_elmnt);
	$keyboard = json_encode($keyboard_pr); 
	$text = 'Hello!';
	$tg->editMess($user, "Привет!!", $keyboard);
	exit();	
}

?>

