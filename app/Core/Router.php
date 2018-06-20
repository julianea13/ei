<?php
namespace ThisApp\Core;
#llamar las instancias
use \Illuminate\Http\Request;
use \ThisApp\Aplication\Security\Session;
use \ThisApp\Aplication\System\Config;
use \ThisApp\Aplication\Security\ErrorLog;

class Router extends Request
{	
	
	//para que entre de una al login sin hacer validaciones de sesion se configuro por defecto Login como controlador
	protected $_controller = 'Login';
	protected $_method = 'index';

	public function __construct(Request $request)
	{	$url = explode('/', filter_var(trim($request->getPathInfo(),'/'),FILTER_SANITIZE_URL));		
		//$this->_params["queryString"] = $request->all();//Request::createFromGlobals()->request->all();
		if(file_exists('../app/Controllers/'.ucfirst(strtolower($url[0])).'.php'))
		{
			$this->_controller = ucfirst(strtolower($url[0]));
			unset($url[0]);
		}elseif(ucfirst(strtolower($url[0])) != ""){
			ErrorLog::throwNew("pagina no encontrada", debug_backtrace(),'404');
		}

		//require_once '../app/controllers/'.$this->_controller.'.php';
		$theController = "ThisApp\Controllers\\".$this->_controller;		
		if (isset($url[1]) and method_exists($theController, $url[1])) {
			$this->_method = $url[1];	
			unset($url[1]);			
		}
	

		//validar si tiene sesion		
		if (Session::exists('user')){
	        //si, existe la sesion
				if ($this->_controller == 'Login' && $this->_method != "out") {	        
		        //intenta ingresar al login con la sesion activa 
					header("Location: /proyectos");
					exit;
				}
				//controlar el acceso segun los permiso en sesion
				//apc = allowed pages collection
				$apc = Session::get('user')['menu'];
				// dump_exit($apc);
				//finded siempre es falso a menos que la ruta solicitada sea permitida
				$finded = false;
				$path_to = strtolower($this->_method) == 'index' ? strtolower("/".$this->_controller) : strtolower("/".$this->_controller."/".$this->_method);
				foreach ($apc as $k => $v){				
				 	if($path_to == $v->url){			 		
				 		$finded = true;
				 		break;
				 	}
				 }
				 if($this->_controller == 'Historial' && $this->_method == 'nuevo'){
				 	$finded = true;
				 }
				if ($finded == false and !$request->ajax() and $this->_controller != 'Err' and ($this->_controller != 'Login' and $this->_method != 'out')) {
					ErrorLog::throwNew("No tiene permiso", debug_backtrace(),'403');				
				}		
			
				
		}else{ 
	        //no, no existe la sesion
			if ($this->_controller != 'Login') {
	          //ingresa al login sin sesion activa 
				if(!$request->ajax()){
					header("Location: /login");
	          		#token
					exit;
				}
			}
		}


	    //fin validacion de la sesion
		$this->_controller = new $theController($request);

		call_user_func_array([$this->_controller, $this->_method], array("actions" => array_values($url)));
	}
}
