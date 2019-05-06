<?php 
namespace Base;
use \PDO;
/**
 * 
 */
class DB
{

    public static function con() {
    	echo 'Ok, connect' . '<br><br>';
        return new PDO('mysql:host=localhost;dbname=spone7qy_telebot', 'spone7qy_telebot', 'Size789789');
    }

    public static function query($query, $params = array()) {
        $stmt = self::con()->prepare($query);
        $stmt->execute($params);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }

    //поиск по chat_id
    public static function getById($table, $id){
        $stmt = self::con()->prepare('SELECT * FROM ' . $table . ' WHERE chat_id = ?'); 
        $stmt->execute(array($id));
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }
    //добавление нового user
    public static function saveUser(User $user){
        $stmt = self::con()->prepare('INSERT INTO oop_users (chat_id, username, first_name) VALUES (?,?,?)'); 
        return $stmt->execute(array($user->chat_id, $user->username, $user->name));
    }

    //обновление в БД по user
    public static function updateUser($user){
        $stmt = self::con()->prepare('UPDATE oop_users SET obj = :obj WHERE chat_id = :chat_id'); 
        return $stmt->execute([':obj' => serialize($user), ':chat_id' => $user->chat_id]);
    }

    //выдача информации по user
    public static function takeInfo($chat_id){
        $stmt = self::con()->prepare('SELECT obj FROM oop_users WHERE chat_id = ?'); 
        $stmt->execute(array($chat_id));
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $data = unserialize($data[0]['obj']);
        if (!empty($data)) {
            return $data;
        }
        return false;
    }
}
