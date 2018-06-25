<?php
namespace ThisApp\Models;

use \ThisApp\Aplication\System\DB;
use \ThisApp\Aplication\System\Mail;
use \ThisApp\Entities\User as eUser;
use \ThisApp\Aplication\Security\Session;
use \ThisApp\Aplication\Security\Hash;
use \ThisApp\Aplication\System\Config;
use \ThisApp\Aplication\Security\ErrorLog;
use \Exception;

class User
{
	public $_data,
			$_db;
	public function __construct()
	{
		$this->_db = DB::getInstance();
	}

	public function getMenuByRol($rol)
	{		
		$sql = "SELECT m.* from menu_rol mr join rol r on mr.id_rol = r.id join menu m on mr.id_menu = m.id where m.active = 1 and r.id = :rol ORDER BY m.order;";
		if ($this->_db->query($sql, array("rol" => $rol))->error())
			ErrorLog::throwNew( $this->_db->errDesc(), debug_backtrace(), '500');
		return $this->_db->results();
	}

	public function getPermisoRol($rol)
	{		
		$sql = "SELECT e.nombre, c.c,c.r,c.u,c.d FROM entidades_rol c JOIN entidades e on e.id = c.id_entidad where c.id_rol = :rol;";
		if ($this->_db->query($sql, array("rol" => $rol))->error())
			ErrorLog::throwNew( $this->_db->errDesc(), debug_backtrace(), '500');
		return $this->_db->results();
	}
	public function setUser(eUser $user)
	{
		if (!$this->_db->insert('users', $user->inserts())->error()) {
			$rpta = $this->_db->lastId();
			if (is_numeric($rpta)) {
				

				$dominio_trebol = Config::get('system/domain');
				$confirmationLink = $dominio_trebol.'/login/confirm/'.$user->getCertificationLink();
				$name = $user->getFullName();			
				$mail = $user->getMail();
				$subject = "Solicitud de confirmación de cuenta";
				$variables_para_reemplazar = array(
								"nombreRecibe" => $name,
								"link" => $confirmationLink,
								"email" => $mail,
								"dominio" => $dominio_trebol,
								);

				$html = __DIR__."/../../public/mail/mail.html";
				$text = "Copia y pega el siguiente enlace en tu navegador para confirmar tu registro. ".$confirmationLink;
				return Mail::send($name, $mail, $subject, $text, $html, $variables_para_reemplazar);
			}
		}else{
			return false;
		}
	}

	


	public function edit($user, $rol)	
	{		
		$eUser = new eUser();    
		if($rol == 1){ 
		$eUser->setFullName($user['full_name']);
		$eUser->setLastName($user['last_name']);
		$eUser->setProfileImage($user['image_profile']);
		$eUser->setRol($user['rol']);		
		$eUser->setIdFranchise($user['franchise']);				
		$eUser->setIdGender($user['gender']);		

     if($user['passw'] == ""){
       $pass = $user['default1'];
       $eUser->setPass($pass);
     
     if (!$this->_db->update("users",array("field"=> "id", "value"=>$user['default'] ),$eUser->updatesB())->error()) {
			$rpta = $this->_db->lastId();
			return true;
		}else{
			return false;
		}
     }else{
     		$salt = Hash::salt(32);
      	$pass = Hash::make($user['passw'], $salt);
      	$eUser->setSalt($salt);
      	$eUser->setPass($pass);      	
      if (!$this->_db->update("users",array("field"=> "id", "value"=>$user['default'] ),$eUser->updates())->error()) {
			$rpta = $this->_db->lastId();
			return true;
		}else{
			return false;
		}
     }
	
		}else{		
		if($user['passw']==""){
         $pass = Session::get('user')['pass'];
		}else{
        $pass = Hash::make($user['passw'],Session::get('user')['salt']);
		}		
	  $eUser->setFullName($user['full_name']);
		$eUser->setLastName($user['last_name']);
		$eUser->setProfileImage($user['image_profile']);
		$eUser->setPass($pass);				
	  $eUser->setIdGender($user['gender']);			
		if (!$this->_db->update("users",array("field"=> "id", "value"=>$user['default'] ),$eUser->updatesA())->error()) {
			$rpta = $this->_db->lastId();
			return true;
		}else{
			return false;
		}
		}
		
	}
	

	public function credentials($user){
		$id_usuario = Session::get("user")["id"];
		$pass = $user["claven"];
      	$repass = $user["reclaven"];
      	if($pass != $repass)
      			return 2;
      	$eUser = $this->validateUser("id", $id_usuario);
      	$inputPassword = Hash::make($user['clavev'], $eUser->getSalt());
		if ( $eUser->getPass() === $inputPassword ){
	      	$salt = Hash::salt(32);
	      	$pass = Hash::make($pass,$salt);
	      	if($this->updateUser($id_usuario, array("pass"=>$pass, "salt"=>$salt)))
	      		return 1;
	      	else
	      		return 0;
		}else{
			return 3;
		}
	}

	public function recoverPass($id, $email,$pass,$passu,$salt, $nombre){
		if($clave = $this->updateUser($id,["pass"=>$passu,"salt"=>$salt])){			
			$confirmationLink = '/login/confirm/';
			$mailTo = $email;
			$subject = "Solicitud de recuperación de contraseña";
			$replaceVars = array(
							"pass"=>$pass,
							"email"=>$mailTo,
							"link"=>'/login',
							"nombreRecibe"=> $nombre
							);
			$html = $archivo = __DIR__."/../../public/mail/recover.html";
			$text = "La clave fué generada aleatoriamente, recuerde cambiarla al ingresar. Su clave es: ".$pass;
			return Mail::send("",$mailTo, $subject, $text, $html, $replaceVars);
		}else{
			return false;
		}
	}

	public function current(){
		$id_usuario = Session::get("user")["id"];
		return $this->validateUser("id", $id_usuario);
	}
	public function validateUser($field, $value, $activo = 1){	
		$sql = "SELECT * FROM users where {$field} = :value";
		if ($this->_db->query($sql, array("value" => $value))->error() === false ){
			if($this->_db->count() > 0){
				return $this->makeEntity($this->_db->first(), true);
			}
		}
		return false;
	}
	
	public function userExists($field, $value, $activo = 1){
		$table = 'users';
		$sql = "SELECT COUNT(id) as cant FROM {$table} WHERE {$field} = :value";
		if ($this->_db->query($sql, array("value" => $value))->error() === false ) {
				if($this->_db->first()->cant == 0 )
					return false;
				else
					return true;
			}else{
				return false;
			}
	}

	public function updateUser($id, array $fields){	
	
		if($this->_db->update("users", ["field"=>"id", "value"=>$id], $fields)->error())	
			return false;
		else
			return true;
	}

	private function makeEntity($user, $full = false){		
				
		$eUser = new eUser();
	  $eUser->setId($user->id);
   	$eUser->setName($user->name);
   	$eUser->setLastName($user->last_name);
   	$eUser->setNick($user->nick);
   	$eUser->setSalt($user->salt);
   	$eUser->setPassword($user->password);
   	$eUser->setIdRol($user->id_rol);
   	$eUser->setImage($user->image);
   	$eUser->setActive($user->active);
   	$eUser->setEmail($user->email);   	

		return  $eUser;
	}

	public function getId(){
		return Session::get("user")["id"];
	}

	public function crearClave(){
		$s =uniqid("",false);
	    $num = hexdec(str_replace(".","",(string)$s));		
	    $index = 'ABCDEFGHIJNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';
		$arr_index = str_split($index);
		shuffle($arr_index);
		$index = implode($arr_index);
	    $base = strlen($index);
	    $out = '';
	        for($t = floor(log10($num) / log10($base)); $t >= 0; $t--) {
	            $a = floor($num / pow($base,$t));
	            $out = $out.substr($index,$a,1);
	            $num = $num-($a*pow($base,$t));
	        }
	      return $out;
	}
}