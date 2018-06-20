<?php
namespace ThisApp\Aplication\Security;
use \ThisApp\Aplication\Security\Session;
use \ThisApp\Aplication\System\Config;
use \ThisApp\Aplication\System\Response;
/**
*
*/
class Token
{
	public static function create()
	{
		return Session::put("token", sha1(uniqid()));
	}

	public static function validate($token){	
		if (Session::exists("token") && $token === Session::get("token")) {
			Session::delete("token");
			return true;
		}
		return false;
	}
	public static function tokenCheck($token){
			if(!Self::validate($token))				
				Response::ajax(405, 'Missing token');	
		}
}