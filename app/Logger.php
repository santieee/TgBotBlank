<?php 
namespace Base;
/**
 * 
 */
class Logger
{
	public function log($filename,$log){
		file_put_contents($filename . '.txt', json_encode($log));
	}
}


?>