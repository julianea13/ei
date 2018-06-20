<?php
namespace ThisApp\Aplication\Security;

use \ThisApp\Aplication\System\Config;

class ErrorLog
{
	public static function throwNew($err_message, $trace, $code, $ajax = false){
    if($code != '404'){
  		$path = __DIR__ .'/../../Aplication/Security/Log';		
  		$handle = fopen($path.'/ERR_LOG.txt', 'a');
      $text = "======================== ".date('d/m/Y H:i:s')." ========================\r\n\r\n";
      $text = $text."Code: ".$code." - Client: ".Self::getIp()." - User: ".Session::get("user")['mail']." - Class: ".$trace[0]['class']." - Function: ".$trace[0]['function']." - File: ". $trace[0]['file']."- Line: ". $trace[0]['line']. " - Error: ".$err_message."\r\n\r\n";
  		fwrite($handle, $text);
  		fclose($handle);
    } 
    if(!$ajax)
      Self::showError($code);
  }

  private static function showError($code){
    $destination = "/err/show/".$code;
    header("Location: ".$destination);
    exit;
  }

  	private static function getIp(){
  //IP ADDRESS
     $ipaddress = '';
  if (getenv('HTTP_CLIENT_IP'))
      $ipaddress = getenv('HTTP_CLIENT_IP');
  else if(getenv('HTTP_X_FORWARDED_FOR'))
      $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
  else if(getenv('HTTP_X_FORWARDED'))
      $ipaddress = getenv('HTTP_X_FORWARDED');
  else if(getenv('HTTP_FORWARDED_FOR'))
      $ipaddress = getenv('HTTP_FORWARDED_FOR');
  else if(getenv('HTTP_FORWARDED'))
     $ipaddress = getenv('HTTP_FORWARDED');
  else if(getenv('REMOTE_ADDR'))
      $ipaddress = getenv('REMOTE_ADDR');
  else
      $ipaddress = 'UNKNOWN';
  $AgentIp = $ipaddress;

  return $AgentIp;

  }
}