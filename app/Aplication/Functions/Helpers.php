<?php
namespace ThisApp\Aplication\Functions;
/**
*
*/
class Helpers
{

	public static function escape($string)
	{
		return htmlentities($string, ENT_QUOTES, 'UTF-8');
	}

	public static function unWrapResult($array)
	{
		$newValues= array();
		foreach ($array as $key => $value) {
			if (is_array($value))
			{
				$newValues[$key] = unWrapResult($value);
			}
			else
			{
				$newValues[$key] = $value;
			}
		}
		return $newValues;
	}

	public static function toArray($obj, $namespace, $setUtf = true)
	{
		#$fechas(hoy, fecha, nombreResultado)
			    if (is_object($obj)) $obj = (array)$obj;

			    if (is_array($obj)) {
			        $new = array();
			        foreach ($obj as $key => $val) {
			        	if ($key == 'id') {			        		
			        		if(is_string($val))
					        	$val = $setUtf==true ? utf8_encode($val) : $val;
					            $new[str_replace($namespace, "", $key)] = self::toArray($val, $setUtf);
			        	}
			        }
			    } else {
			        $new = $obj;
			    }
		return $new;
	}

	function validateEmail($email)
	{
	 return filter_var($arr['email'],FILTER_VALIDATE_EMAIL);
	}

	function  readCsv($csv, array $headers){		
		$rows = [];
	    while (($datos = fgetcsv($csv, 4500, ";")) !== false) {
	        $numero = count($datos);       
	        if($numero > count($headers))
	        	return false;
	        $columns = [];
	        for ($c=0; $c < $numero; $c++) {
	        	
	            $columns[$headers[$c]] =  $datos[$c];
	        }
	        $rows[] = $columns;
	    }
	    fclose($csv);
	    return $rows;
	}
}