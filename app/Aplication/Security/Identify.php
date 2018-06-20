<?php
namespace ThisApp\Aplication\Security;

/**
*
*/
class Identify
{
	public function login($datos, $proyecto = array())
	{
		Session::put(Config::get("session/session_name"), $datos->id);
		Session::put(Config::get("session/user_name"), $datos->primerNombre." ".$datos->primerApellido);
		Session::put(Config::get("session/user_rol"), $datos->rol);
		Session::put(Config::get("session/user_mail"), $datos->correo);
		if (count($proyecto)>0) {
			Session::put(Config::get("session/proyect_name"),  $proyecto->nombre);
			Session::put(Config::get("session/proyect_id"),  $proyecto->id);
			Session::put(Config::get("session/proyect_events"),  $proyecto->numEventos);
		}

		return  true;
	}

	public function info()
	{
		$datos = array(
			"idLogged"=>Session::get(Config::get("session/session_name")),
			"nombreLogged" => Session::get(Config::get("session/user_name")),
			"rolLogged" => Session::get(Config::get("session/user_rol")),
			"mailLogged" => Session::get(Config::get("session/user_mail")),
			"proyectLogged" => Session::get(Config::get("session/proyect_name")),
			"idProyectLogged" => Session::get(Config::get("session/proyect_id")),
			"eventsLogged" => Session::get(Config::get("session/proyect_events"))
					);
		return  $datos;
	}
	public function logout()
	{
		Session::delete(Config::get("session/session_name"));
		Session::delete(Config::get("session/user_name"));
		Session::delete(Config::get("session/user_rol"));
		return true;
	}

	public function checkPass($password, $oldPass)
	{
		if ($oldPass === Hash::make($password, $this->_data->salt))
			return true;
		return false;
	}

}
