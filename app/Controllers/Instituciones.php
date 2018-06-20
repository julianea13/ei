<?php
namespace ThisApp\Controllers;

use \ThisApp\Core\View;
use \ThisApp\Aplication\System\Config;
use \ThisApp\Aplication\System\Response;
use \ThisApp\Aplication\Security\Session;
use \ThisApp\Aplication\Security\Token;
use \ThisApp\Aplication\Security\Hash;
use \ThisApp\Aplication\Security\ErrorLog;
use \ThisApp\Models\Institutions;
 
class Instituciones {
	
	private $_request,
			$_qs;

	public function __construct($request = null){
		$this->_request =  $request;
		$this->_qs = $request->all();
	}

	public function index($actions = null)
	{		
  $oI = new Institutions;
  $instituciones = $oI->getAll();

		foreach ($instituciones as $k => $p) {
      $p->id = Hash::encrypt($p->id);
    }
    
		$data = array(
			"title"=>"Instituciones",
			"actualPage"=>"posts",
			"ins" => $instituciones			
		);	
	  View::show('instituciones',$data);
	}	
	public function detalle($actions = null)
	{
		if(!isset($actions[0]))
			ErrorLog::throwNew("pagina no encontrada", debug_backtrace(), '404');
		else
			$id = Hash::decrypt($actions[0]);

		if($id == false || !is_numeric($id))
			ErrorLog::throwNew("pagina no encontrada", debug_backtrace(), '404');
		$oP = new Institutions;
		$institucion = $oP->getinstitutions($id);
		$data = array(
			"title"=>"Detalle del post",
			"institucion" => $institucion

		);
	  View::show('instituciones-detalle',$data);
	}
	public function nuevo($actions = null)
	{
	  View::show('nueva-instituciones');
	}	
}
