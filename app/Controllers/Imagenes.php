<?php
namespace ThisApp\Controllers;

use \ThisApp\Core\View;
use \ThisApp\Models\Institutions;
use \ThisApp\Aplication\Security\Hash;
use \ThisApp\Aplication\System\Response;
use \ThisApp\Models\User;
use \ThisApp\Entities\Files;
use \ThisApp\Models\Proyectos;

class Imagenes {
	private $_request,
			$_qs;

	public function __construct($request = null){
		$this->_request =  $request;
		$this->_qs = $request->all();
	}

	public function image($actions = null){

		if(!$this->_request->ajax())
					Response::ajax(1,"ajax");
    $titulo_doc1 = str_replace(' ', '', $_FILES['file']['name']);
    $titulo_doc2 = str_replace(')', '', $titulo_doc1);
		$titulo_doc = str_replace('(', '', $titulo_doc2);

		$ext =strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
			$img = array("png", "jpg", "jpeg", "bmp", "svg", "gif", "tiff");
			$video = array("mp4", "avi", "mpeg", "mov", "wmv", "rm", "flv");
			$audio = array("mp3", "midi", "wav", "wma", "cd", "ogg", "AIF");
		// var_dump($_FILES['file']['size']);
		if ( 0 < $_FILES['file']['error']) {
		var_dump('error '.$_FILES['file']['error']);
		exit;
			Response::ajax(3,"Unhealthy file");
	    }else{
    if(in_array($ext, $img)){
	if ($_FILES['file']['size'] > 2097152){
				Response::ajax(4,"El archivo tiene que pesar menos de 2MB");
			}
						$dir = __DIR__.'/../../public/uploads/'.$actions[0].'/'.$actions[1].'/imagenes';
						$type ="2";
						$contenido = '/uploads/'.$actions[0].'/'.$actions[1].'/imagenes/'.$titulo_doc;
    }elseif(in_array($ext, $video)){
	if ($_FILES['file']['size'] > 20971520){
				Response::ajax(4,"El archivo tiene que pesar menos de 20MB");
			}
				$dir = __DIR__.'/../../public/uploads/'.$actions[0].'/'.$actions[1].'/videos';
				$type ="1";
				$contenido = '/uploads/'.$actions[0].'/'.$actions[1].'/videos/'.$titulo_doc;
    }elseif(in_array($ext, $audio)){
    		if ($_FILES['file']['size'] > 2097152){
				Response::ajax(4,"El archivo tiene que pesar menos de 2MB");
			}
				$dir = __DIR__.'/../../public/uploads/'.$actions[0].'/'.$actions[1].'/audios';
				$type ="3";
				$contenido = '/uploads/'.$actions[0].'/'.$actions[1].'/audios/'.$titulo_doc;
    }else{
    	Response::ajax(4,"El archivo no se puede subir");
    }
	    	if (!file_exists($dir) && !is_dir($dir))
			    mkdir($dir,0755,true);


			$ruta = $dir ."/".$titulo_doc;

		$oProyect = new Proyectos();

		$eFiles = new Files();
		$eFiles->setName($titulo_doc);
   	$eFiles->setFileType($type);
    $eFiles->setBelongs($actions[2]);
   	$eFiles->setIdBelongs($actions[1]);
   	$eFiles->setActive('1');
   	$eFiles->setContenido($contenido);
   	$eFiles->setDescription('Haz clic en el archivo para visualizarlo');
 			if($oProyect->setArchives($eFiles)){
       echo '200';
      }
      else{
        echo '204';
      }
	    	if(move_uploaded_file($_FILES['file']['tmp_name'], $ruta))
	    	{
						Response::ajax(6,"OK");
						$user = Session::get("user");
						$user["img"] = $id."/".$titulo_doc.".".$ext;
						Session::delete("user");
						Session::put("user", $user);
	    	}else{
	    		Response::ajax(7,"Server file system error");
	    	}
	    }
	}


	public function images($actions = null){
		if(!$this->_request->ajax())
			Response::ajax(1,"ajax");
			$titulo_doc = 'img';

			// if (Token::validate($this->_qs["token"]))
			// 	$this->ajaxResponse("error", "unauthorized", "Missing Token");

			// START PROCESS
			if(!$this->_request->hasFile($titulo_doc))
				Response::ajax(2,"no file deliveder");

		if ( 0 < $_FILES[$titulo_doc]['error'] ) {
			Response::ajax(3,"Unhealthy file");
	    }else{

			if ($_FILES[$titulo_doc]['size'] > 16777216)
        	Response::ajax(4,"El archivo tiene que pesar menos de 2MB");
        	$allowed_ext = array("png", "jpg", "jpeg", "bmp", "svg", "gif", "tiff");
        	$ext = strtolower(pathinfo($_FILES[$titulo_doc]['name'], PATHINFO_EXTENSION));
			if(!in_array($ext, $allowed_ext))
        Response::ajax(5,"Extension not allowed");
	    	// $dir = __DIR__.'/../../public/uploads/images/users/'.$id;
	    	$dir = __DIR__.'/../../public/uploads/images/'.$actions[0].'/'.$actions[1];

	    	if (!file_exists($dir) && !is_dir($dir))
			    mkdir($dir,0755,true);

			$ruta = $dir ."/". $titulo_doc.".".$actions[2];

	    	if(move_uploaded_file($_FILES[$titulo_doc]['tmp_name'], $ruta))
	    	{

						Response::ajax(6,"OK");
						$user = Session::get("user");
						$user["img"] = $id."/".$titulo_doc.".".$ext;
						Session::delete("user");
						Session::put("user", $user);
	    	}else{
	    		Response::ajax(7,"Server file system error");
	    	}
	    }
	}

}
