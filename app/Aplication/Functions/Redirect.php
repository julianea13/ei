<?php
//session_start();

namespace ThisApp\Aplication\Functions;
/**
* 
*/
class Redirect
{
	public function to($location)
	{
		header("Location: ".$location);
		exit();
	}

	public function checkRol($rol, $location)
	{	
		if ($rol != Session::get(Config::get("session/user_rol"))) {
			header("Location: ".$location);
			exit();
		}
	}

}