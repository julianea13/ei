<?php
namespace ThisApp\Aplication\Security;
/**
* 
*/
use \ThisApp\Aplication\System\Config;

class Hash
{
	public static function make($string, $salt = ""){
		return hash('sha256',$string.$salt);
	}

	public static function salt($lenght){
    #ojo DEPRECATED
		return mcrypt_create_iv($lenght);
	}

	public static function unique(){
		return self::make(uniqid());
	}

	public static function encrypt($data){
		$hd = Self::getHashData();
		return Self::sanitize(openssl_encrypt($data, $hd['method'], $hd['clave'], 0, $hd['iv']));
	}

	public static function decrypt($enc_data){
		$hd = Self::getHashData();
		return openssl_decrypt(Self::sanitize($enc_data, true), $hd['method'] , $hd['clave'], 0, $hd['iv'] );
	}

	private static function getHashData(){
		return array("clave" => Config::get('system/enc_pass'), 'method' => Config::get('system/enc_method'), 'iv' => substr(openssl_digest(ip2long($_SERVER['SERVER_ADDR']), 'SHA256', true), -16));
	}

	private static function sanitize($data, $out = false){	
		$char = "trebol";
		return $out ? str_replace($char, "/", $data) : str_replace("/", $char, $data);
	}
}