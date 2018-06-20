<?php
namespace ThisApp\Controllers;

use \ThisApp\Core\View;
use \ThisApp\Aplication\System\Config;
use \ThisApp\Aplication\System\Response;
use \ThisApp\Aplication\Security\Session;
use \ThisApp\Aplication\Security\Token;
use \ThisApp\Aplication\Security\Hash;
use \ThisApp\Entities\User as eUser;
use \ThisApp\Models\User;

class Login {
	
	private $_request,
			$_qs;

	public function __construct($request = null){
		$this->_request =  $request;
		$this->_qs = $request->all();
	}

	public function index($actions = null)
	{		    
// dump_exit($institution);
		$data = array(
			"title"=>"Bienvenido",
			"actualPage"=>"login",     

		);	
	  View::show('login',$data);
	}

	public function recuperar($params = null)
	{
		if(!$this->_request->ajax())
		Response::ajax(403, 'Missing token');
		
		$user = $this->_qs;		
		$oUser = new User();

		if ($eUser = $oUser->validateUser("mail", $user['correo'])) {	

			if($eUser->getActive() != 1)						
				Response::ajax(403, 'Activación pendiente');

			$claveEnviar = $oUser->crearClave();
			$salt = Hash::salt(32);
			$clave = Hash::make($claveEnviar, $salt);
			if($eUser = $oUser->recoverPass($eUser->getId(),$eUser->getMail(),$claveEnviar,$clave,$salt, $eUser->getFullName(), $eUser->getlastName()))
				Response::ajax(200, 'Actualizado');
			else
				Response::ajax(500, 'Error al procesar envio/escritura');
		}else{
			Response::ajax(404, 'No encontrado');	
		}
	}

	public function in($actions = null)
	{
	
		if(!$this->_request->ajax())
			Response::ajax(403, 'Missing token');
	  	Token::tokenCheck($this->_qs['token']);
	   	$oUser = new User();	   
		if ($eUser = $oUser->validateUser("email", $this->_qs['mail'])) {
			if($eUser->getActive() != 1)
				Response::ajax(2,"No has activado la cuenta");
			$dbPassword = $eUser->getPassword();
			$inputPassword = Hash::make($this->_qs['pass'], $eUser->getSalt());
			if ($dbPassword === $inputPassword ) {
				Session::setFlash("la la-check","Hola ".$eUser->getName());
				if($this->logUserIn($eUser) == 1)
					Response::ajax(1,"ok");
			}else{				
			 	Response::ajax(200,"Contraseña incorrecta");
			}
		}else{
			 Response::ajax(201,"Correo incorrecto");
		}
	}

	public function getFranchise($actions = null)
	{
		if(!$this->_request->ajax())
		Response::ajax(403, 'Missing token');	  	
	   	$oFranchise = new Franchises();
		if ($oFranchise->getFranchisesIns($actions[0])) {		
			Response::ajax(200, $oFranchise->getFranchisesInst($actions[0]));				
		}else{
			 Response::ajax(201,"No");
		}
	}

	public function nuevo($actions = null){	
		if(!$this->_request->ajax())
			Response::ajax(403, 'Missing token');
	  	Token::tokenCheck($this->_qs['token']);
		$user = $this->_qs;
		$oUser = new User();
		if($oUser->userExists('mail', $user['email']))
			Response::ajax(202, 'El correo ingresado ya existe en el sistema');
		if($oUser->userExists('nick', $user['nick']))
			Response::ajax(202, 'El nombre de usuario ingresado ya existe en el sistema');

		if ($user['nick'] == "" || strlen($user['nick']) < 4 || strlen($user['nick']) > 10  ||  !ctype_alnum($user['nick']))
			Response::ajax(202, 'El nombre de usuario no es válido');

		if ($user['passw'] == "" || strlen($user['passw']) < 6)
			Response::ajax(202, 'la clave no es válida');
	

		if ($user['passw'] === $user['repass']){

			$eUser = new eUser();

			$salt = Hash::salt(32);
			$pass = Hash::make($user['passw'], $salt);

			$eUser->setNick($user['nick']);
			$eUser->setMail($user['email']);
      $eUser->setRol(2);
      $eUser->setIdFranchise($user['franchise']);
      $eUser->setIdGender($user['gender']);
      $eUser->setFullName($user['user_name']);
			$eUser->setLastName($user['user_last_name']);
			$eUser->setProfileImage($user['image']);
			$eUser->setPass($pass);
			$eUser->setSalt($salt);

			$unique = Hash::unique();
			$eUser->setCertificationLink($unique);

			if($oUser->setUser($eUser)){
				Response::ajax(200, 'Usuario creado correctamente');
			}else{
				Response::ajax(500, 'No podemos crear su usuario, por favor intenta mas tarde.');
			}
		}else{
			Response::ajax(202, 'las claves no coinciden');
		}
	}

	public function confirm($actions){
		if(count($actions) == 1){			
			$oUser = new User();
			$eUser = $oUser->validateUser("certification_link",$actions[0]);		
	
			if ($eUser) {				
				if ($eUser->getActive() == 1) {
					header("Location: /login");
					exit;
				}
				if($oUser->updateUser($eUser->getId(),["active"=>1])){
           Session::setFlash("la la-check","Has activado satisfactoriamente la cuenta ".$eUser->getFullName());
					if($this->logUserIn($eUser)){
						header("Location: /".Config::get("system/home"));
						exit;
					}
				}
			}
		}
		header("Location: /login");
		exit;	
	}

	public function out($params = null)
	{		
		Session::delete(Config::get("session/menu_sent"));
		Session::delete('user');
		Session::destroy();
		header("Location: /login");
		exit;
	}

	private function fin(){
		if(intval(date('d')) > 4 or intval(date("Y")) > 2017 ){
			echo "date";
			exit;
		}
	}

	private function logUserIn($user){

		$oU = new User();	
		
		$menu = $oU->getMenuByRol($user->getIdRol());
		$permisos = $oU->getPermisoRol($user->getIdRol());
		$usd = [ 
						"id" => $user->getId(),
		        "nombre" => $user->getName(),		        
		        "apellido" => $user->getLastName(),	        
		        "nick" => $user->getNick(),	        
		        "rol" => $user->getIdRol(),		        
		        "image" => $user->getImage(),	        
		        "email" => $user->getEmail(),
		        "menu" => $menu,	
            "permisos"=>$permisos		        
						];
		//$datosJson = json_encode($userLogged);
		Session::put("user", $usd);
		//crear rol description en session
		return 1;
	}

		
}
