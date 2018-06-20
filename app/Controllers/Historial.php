<?php
namespace ThisApp\Controllers;

use \ThisApp\Core\View;
use \ThisApp\Aplication\System\Config;
use \ThisApp\Aplication\System\Response;
use \ThisApp\Aplication\Security\Session;
use \ThisApp\Aplication\Security\Token;
use \ThisApp\Aplication\Security\Hash;
use \ThisApp\Aplication\Security\ErrorLog;
use \ThisApp\Models\Proyectos as mProyectos;
use \ThisApp\Entities\Proyect;
use \ThisApp\Models\Files;
use \ThisApp\Models\Historial as mHistorial;
 
class Historial {
	
	private $_request,
			$_qs;

	public function __construct($request = null){
		$this->_request =  $request;
		$this->_qs = $request->all();
	}
	public function detalle($actions = null)
	{		
		if(!isset($actions[0]))
			ErrorLog::throwNew("pagina no encontrada", debug_backtrace(), '404');	
		else
			$id = Hash::decrypt($actions[0]);	

		if($id == false || !is_numeric($id))
			ErrorLog::throwNew("pagina no encontrada", debug_backtrace(), '404');	
		$oP = new mProyectos;
		$oF = new Files;
		$oH = new Historial;
		$proyecto = $oP->getSingle($id);
	
		$files = $oF->getFile('1',$id);	
		$files_historial = $oF->getCountFile($id,'3');	
		$historial = $oH->getHistorial('1',$id);	
		$filesAtt = $oF->getFileAttr('1',$id);	
			foreach ($historial as $k => $p) {
      $p->id = Hash::encrypt($p->id);
    }
    foreach ($files_historial as $k => $p) {
      $p->id_belongs = Hash::encrypt($p->id_belongs);
    } 
      $proyecto->id_institution = Hash::encrypt($proyecto->id_institution);
   
    

		$data = array(
			"title"=>"Detalle del post",
			"proyecto" => $proyecto,
			"files"=>$files,
			"files_historial"=>$files_historial,
			"filesAtt"=>$filesAtt,
			"historial"=>$historial,
			
		
		);	
	  View::show('proyectos_detalle',$data);
	}	
	public function crear($actions = null){	  		
	  Response::validate($this->_request->ajax(), 'proyectos', 'c');
 
    $oProyect = new mProyectos();   
		$Proyect = $this->_qs;
		$usd = Session::get("user");
		$eProyect = new Proyect();
		$eProyect->setName($Proyect['name']);
   	$eProyect->setDescription($Proyect['text_long']);
    $eProyect->setTag($Proyect['tags']);
   	$eProyect->setIdCategory($Proyect['category']);  
   	$eProyect->setActive('1');  
   	$eProyect->setIdCf($usd['id']);
   	$eProyect->setCa(date("Y-m-d H:i:s"));
   	$eProyect->setIdInstitution($Proyect['institution']);  	  
 			if($oProyect->setProyect($eProyect)){
        Session::setFlash('Creacion del proyecto', 'El proyecto fue creado con exito.');
        Response::ajax(200, 'Creado');
      }	  	        
      else{
        Response::ajax(204, 'No creado');
      }
	 }	

	public function nuevo($actions = null)
	{   
   if(!isset($actions[0]) and !isset($actions[1]) or !isset($actions[1]))
      ErrorLog::throwNew("pagina no encontrada", debug_backtrace(), '404'); 
		$oP = new mProyectos;
		$category = $oP->getCategory();
		$idP = $oP->getId()->proyect;    
		$idPe = Hash::encrypt(2);    
	
		$data = array(
		"title"=>"Nuevo Historial"	
	);
	  View::show('nuevo_historial',$data);
	}
}
