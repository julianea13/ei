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
use \ThisApp\Entities\Institution;
use \ThisApp\Entities\Municipio;
use \ThisApp\Models\Proyectos as mProyectos;
use \ThisApp\Models\Files;
use \ThisApp\Models\Historial;

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
    $oh = new Historial;
    $opr= new mProyectos;

    $historial=$oh->getHistorial(2,$id);
    $projects=$opr->getInstitutionById($id);


    $institucion = $oP->getinstitutions($id);
    $data = array(
      "title"=>"Detalle del post",
      "institucion" => $institucion,
      "proyectos" => $projects,
      "historial" => $historial,
      "cripted" => $actions[0]
    );
    View::show('instituciones-detalle',$data);
  }
//Create
  public function crear($actions = null){
	  Response::validate($this->_request->ajax(), 'institution', 'c');

    $oInstitution = new Institutions();
		$Institution = $this->_qs;
		$usd = Session::get("user");
		$eInstitution = new Institution();
   	$eInstitution->setName($Institution['name']);
    $eInstitution->setImage($Institution['portada']);
   	$eInstitution->setShield($Institution['shield']);
   	$eInstitution->setActive('1');
   	$eInstitution->setMunicipio($Institution['type']);
 			if($oInstitution->setInst($eInstitution)){
        Session::setFlash('Creacion del proyecto', 'El proyecto fue creado con exito.');
        Response::ajax(200, 'Creado');
      }
      else{
        Response::ajax(204, 'No creado');
      }
	 }
  public function nuevo($actions = null)
  {
    $oP = new Institutions;
    $municipio = $oP->getMunicipio();
    $idI = ++$oP->getId()->institution;

    $idIe = Hash::encrypt($idI);

    $data = array(
      "title"=>"Nuevo proyecto",
      "municipio"=>$municipio,
      "id_instEn"=>$idIe,
      "id_inst"=>$idI
    );
    View::show('nueva-instituciones',$data);
  }
  //Editar
  public function editar($actions = null)
  {

    if(!isset($actions[0]))
    ErrorLog::throwNew("pagina no encontrada", debug_backtrace(), '404');

    $id = Hash::decrypt($actions[0]);
    if($id == false || !is_numeric($id))
    ErrorLog::throwNew("pagina no encontrada", debug_backtrace(), '404');

    $oInstitutions = new Institutions();
    $institution = $oInstitutions->getInstitutionById($id);
    $municpios=$oInstitutions->getMunicipio();
    $id_institution = Hash::encrypt($institution->id);
    // var_dump($municpios);
    // exit;
    $data = array(
      "title"=>"Editar institucion - Trebol",
      "actualPage"=>"nuevo_sede",
      "institucion"=>$institution,
      "municipios"=>$municpios,
      "id" =>$actions[0],
      "id_en" =>$id_institution
    );
    View::show('edit-institucion',$data);
  }
  //Accion de editar
  public function edit($actions = null){
    if(!$this->_request->ajax())
    ErrorLog::throwNew("pagina no encontrada", debug_backtrace(), '500');
    $oS = new Institutions();
    // var_dump($this->_qs);
    // exit;
    $dec_id = $this->_qs['i_id'];
    $this->_qs['i_id'] = $dec_id;

    if($oS->edit($this->_qs)){
      Session::setFlash('Edición de institución', 'Edición finalizada con exito');
      Response::ajax(200, 'Editado');
    }
    else{
      Response::ajax(204, 'No Editado');
    }
  }
  public function delete($actions = null){
    if(!$this->_request->ajax())
      Response::ajax(403, 'Missing token');
      Token::tokenCheck($this->_qs['token']);

      $id_institucion = Hash::decrypt($this->_qs['id_institucion_delete']);
      $oP = new Institutions();
      if($oP->delete($id_institucion))
        Response::ajax(200, 'Eliminado');
      else
        Response::ajax(204, 'No eliminado');
  }
  public function restore($actions = null){
    if(!$this->_request->ajax())
      Response::ajax(403, 'Missing token');
      Token::tokenCheck($this->_qs['token']);
      $id_institucion = Hash::decrypt($this->_qs['id_institucion_restore']);

      $oP = new Institutions();
      if($oP->restore($id_institucion))
        Response::ajax(200, 'Restaurado');
      else
        Response::ajax(204, 'No restaurado');

  }
}
