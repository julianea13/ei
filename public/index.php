<?php

ini_set("display_errors", 1);
session_start();
date_default_timezone_set('America/Bogota');
require_once __DIR__ . '/../vendor/autoload.php';
use \ThisApp\Core\Router;
use \Illuminate\Http\Request;
new Router(Request::capture());

/*QUITAR EN PRODUCCION*/
function dump_exit($misc){
    highlight_string("<?php\n\$data =\n" . var_export($misc, true) . ";\n?>");
    exit;
}
/*QUITAR EN PRODUCCION*/