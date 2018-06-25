<?php
namespace ThisApp\Controllers;
use \ThisApp\Core\View;
use \ThisApp\Models\User;
use \ThisApp\Entities\User as eUser;
use \ThisApp\Aplication\Security\Hash;
use \ThisApp\Aplication\System\Response;
use \ThisApp\Aplication\Security\Token;
use \ThisApp\Models\Usuarios as mUsuarios;
use \ThisApp\Models\Historial;
use \ThisApp\Models\Proyectos;
use \ThisApp\Aplication\Security\Session;
use \ThisApp\Aplication\Security\ErrorLog;

class Usuarios {
  private $_request,
      $_qs;
  public function __construct($request = null){
    $this->_request =  $request;
    $this->_qs = $request->all();
  }
  public function index($actions = null)
  {
    $oUser = new mUsuarios();    
    $users = $oUser->getAll();   
    foreach ($users as $k => $u) {
      $u->id = Hash::encrypt($u->id);
    }   
    $data = array(
      "title"=>"Usuarios - Trebol",
      "actualPage"=>"ver_usuario",
      "users" => $users
    );
    View::show('usuarios',$data);
  }
  public function nuevo($actions = null)
  {

    $oUser = new mUsuarios();  
     $id = ++$oUser->getIdP()->users;
    $rol = $oUser->getRol();
    $data = array(
      "title"=>"Nuevo usuario",
      "actualPage"=>"nuevo_usuario",
      "rol"=>$rol,
      "id"=>$id
        
    );
    View::show('nuevousuario',$data);
  }
    public function editar($actions = null)
  {
    if(!isset($actions[0]))
      $id_user = Session::get('user')['id'];      
         
    else
      $id_user = Hash::decrypt($actions[0]);  
   if($id_user== Session::get('user')['id'])
     $id_f = Hash::encrypt(Session::get('user')['id']);
   else
     $id_f = $actions[0];
 
    if($id_user == false)
      ErrorLog::throwNew("pagina no encontrada", debug_backtrace(), '404');
     $oUser = new mUsuarios();
     $user = $oUser->getUserById($id_user);     
     $rol = $oUser->getRol();  
    $data = array(
      "title"=>"Editar usuario - Trebol",
      "actualPage"=>"nuevo_usuario",
       "rol"=>$rol,
       "id"=>$id_user,    
       "id_f"=>$id_f,
      "user"=>$user      
    );
    View::show('editarusuario',$data);
  }
 
  public function edit($actions = null){
    if(!$this->_request->ajax())
      ErrorLog::throwNew("pagina no encontrada", debug_backtrace(), '500');
     $dec_id = Hash::decrypt($this->_qs['default']); 
        $this->_qs['default'] = $dec_id;
      $oU = new mUsuarios();
      if($oU->edituser($this->_qs,Session::get('user')['rol'])){
        Session::setFlash('Editar usuario', 'Edición finalizada con exito');
        Response::ajax(200, 'editado');
      }else{
        Response::ajax(204, 'No editado');;
      }
  }
   public function delete($actions = null){
    if(!$this->_request->ajax())
      Response::ajax(403, 'Missing token');
      // Token::tokenCheck($this->_qs['token']);
      $id_user = Hash::decrypt($this->_qs['id_user_delete']);
      $oU = new mUsuarios();
      if($oU->delete($id_user))
        Response::ajax(200, 'Eliminado');
      else
        Response::ajax(204, 'No eliminado');
      
  }
   public function restore($actions = null){
    if(!$this->_request->ajax())
       Response::ajax(403, 'Missing token');
     // Token::tokenCheck($this->_qs['token']);
      $id_user = Hash::decrypt($this->_qs['id_user_restore']);
      $oU = new mUsuarios();
      if($oU->restore($id_user))
       Response::ajax(200, 'Restaurado');
      else
       Response::ajax(204, 'No restaurado');
      
  }
  public function crear($actions = null){     
    Response::validate($this->_request->ajax(), 'usuarios', 'c');
    $user = $this->_qs;   
    $oUser = new mUsuarios();
    $salt = Hash::salt(32);
  
    if($oUser->userExists('email', $user['email']))
      exit('El E-mail ya esta en uso'); 
      $clave = Hash::make($user['pass'], $salt);
      $eUser = new eUser();
      $eUser->setName($user['name']);
      $eUser->setLastName($user['last_name']);
      $eUser->setNick($user['user']);
      $eUser->setSalt($salt);
      $eUser->setEmail($user['email']);
      $eUser->setPassword($clave);        
      $eUser->setIdRol($user['rol']);
      $eUser->setActive(1);
      $eUser->setImage($user['image_profile']);     
 
      if($oUser->setUser($eUser)){ 
       Session::setFlash('Crear usuario', 'Creación finalizada con exito');
        Response::ajax(200, 'Creado');
         }          
      else{
       Response::ajax(204, 'No Creado'); 
      }
   }  
  
  public function detalle($actions = null)
  {
    if(!isset($actions[0]))
      ErrorLog::throwNew("pagina no encontrada", debug_backtrace(), '404'); 
    else
      $id_user = Hash::decrypt($actions[0]);    
    if($id_user == false || !is_numeric($id_user))
      ErrorLog::throwNew("pagina no encontrada", debug_backtrace(), '404');
   
  $mU = new mUsuarios();
  $mH = new Historial();
  $mP = new Proyectos();
  $usuarios = $mU->getSingle($id_user);
  $historial = $mH->getHistorialbyuser($id_user);
  $proyectos = $mP->getProyectbyuser($id_user);
  // dump_exit($historial);
 foreach ($historial as $k => $p) {
      $p->id = Hash::encrypt($p->id);
    }
     foreach ($proyectos as $k => $p) {
      $p->id = Hash::encrypt($p->id);
    }
    $data = array(
      "title"=>"Detalle usuario",
      "actualPage"=>"usuarios/detalle",
      "user"=>$usuarios,     
      "hitorial"=>$historial,
      "proyecto"=> $proyectos    
    );
    View::show('usuario_detalle',$data);
  }
    public function yo($actions = null)
  {
   
    $oUser = new User();
    $oProyect = new Proyect();
   
    $users = $oUser->getCustom(Session::get('user')['id']);  
    $proyect = $oProyect->getFromUser(Session::get('user')['id']);  
    $users->id_institution = Hash::encrypt($users->id_institution);  
     foreach ($proyect as $k => $p) {
      $p->id_clover = Hash::encrypt($p->id_clover);
    }     
    $data = array(
      "title"=>"Usuarios - Trebol",
      "actualPage"=>"usuarios/detalle",
      "user" => $users,
      "id" => $Hash::encrypt(Session::get('user')['id']),
      "proyectos" => $proyect
    );
    View::show('usuario_detalle',$data);
  }
 
}
