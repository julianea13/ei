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
use \ThisApp\Entities\History;
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
    $historial = new mHistorial();
    $history =  $historial->getSingle($id);

    $oF = new Files;
    $files = $oF->getFile('3',$id);   
    $filesAtt = $oF->getFileAttr('3',$id);
    
    $data = array(
      "title"=>"Detalle del post",
      "history" => $history,
      "files"=>$files,   
      "filesAtt"=>$filesAtt ,
      "history_id" => $actions[0]     
    );  
    View::show('historial_detalle',$data);
  }   
    public function editar($actions = null)
  {   
    if(!isset($actions[0]))
      ErrorLog::throwNew("pagina no encontrada", debug_backtrace(), '404');     
    $historial = new mHistorial();
    $history =  $historial->getSingle(Hash::decrypt($actions[0])); 
    // dump_exit();
if(Session::get('user')['id'] != $history->id_cf or Session::get('user')['id'] != 1)
  ErrorLog::throwNew("pagina no encontrada", debug_backtrace(), '403'); 
    $data = array(
    "title"=>"Editar Historial",
    "historia"=>$history,
    "id_history"=>$actions[0]
    );
    View::show('editar_historial',$data);
  }   

     public function edit($actions = null){
    if(!$this->_request->ajax())
      ErrorLog::throwNew("pagina no encontrada", debug_backtrace(), '403');
   
        $oP = new mHistorial();
        $rol_user = Session::get('user')['rol'];
        $dec_id = Hash::decrypt($this->_qs['default']);  
        $this->_qs['default'] = $dec_id;
        $idCf = $oP->getIdCf($dec_id)->id_cf;
   
      if(!($rol_user == 1 or Session::get('user')['id'] == $idCf)){
          ErrorLog::throwNew("No tiene permiso", debug_backtrace(),'403');  
      }
      
      if($oP->edit($this->_qs)){
        Session::setFlash('Edición de historial', 'Edición finalizada con exito');
        Response::ajax(200, 'Editado');
      }
      else{
        Response::ajax(204, 'No editado');

      }
  }

	public function nuevo($actions = null)
	{   
   if(!isset($actions[0]) and !isset($actions[1]) or !isset($actions[1]))
      ErrorLog::throwNew("pagina no encontrada", debug_backtrace(), '404'); 
		$oP = new mHistorial;		
		$idP = $oP->getId()->history;    
		$idPe = Hash::encrypt($idP);  

		$data = array(
		"title"=>"Nuevo Historial",
    "id_history" => $idP, 
    "id_historyEn" => $idPe,
    "id_belongs"=>$actions[1],
    "belongs"=> $actions[0]
	);
	  View::show('nuevo_historial',$data);
	}

    public function crear($actions = null){   
    Response::validate($this->_request->ajax(), 'proyectos', 'c');  
    $oHistory = new mHistorial;     
    $History = $this->_qs;
    $usd = Session::get("user");
    $eHistory = new History();
    $eHistory->setDescription($History['text_long']);
    $eHistory->setCa(date("Y-m-d"));
    $eHistory->setIdBelongs(Hash::decrypt($History['id_belongs']));
    $eHistory->setBelongs(Hash::decrypt($History['belongs']));  
    $eHistory->setActive(1);  
    $eHistory->setIdCf($usd['id']);
    $eHistory->setTags($History['tags']);

      if($oHistory->setHistory($eHistory)){
        Session::setFlash('Creacion de hitorial', 'El proyecto fue creado con exito.');
        Response::ajax(200, 'Creado');
      }             
      else{
        Response::ajax(204, 'No creado');
      }
   }  
}
