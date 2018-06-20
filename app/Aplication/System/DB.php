<?php
namespace ThisApp\Aplication\System;
//php data objet para deifinir cualquier base e datos c
use PDO;
use Exception;
class DB {
	private static $_instance = null;
	private $_pdo,
	$_query,
	$_error =  false,
	$_errDesc = "",
	$_results,
	$_count = 0,
	$_lastId = 0;
	private function __construct(){
		try {
			//$users = ['ciero_superev2', 'ciero_superev22', 'ciero_superev23', 'ciero_superev24', 'ciero_superev25'];
			$this->_pdo = new PDO('mysql:host='.Config::get('mysql/host').';dbname='.Config::get('mysql/db'),Config::get('mysql/username'),Config::get('mysql/password'),array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
		} catch (PDOException $e) {
			die($e->getMessage());
		}
	}
/*
	Metodos base del sistema
*/
	public static function getInstance(){
		if (isset(self::$_instance) === false) {
			self::$_instance = new DB();
			//self::$_instance->query("SET NAMES utf8");
		}
		return self::$_instance;
	}
	
	private function clean(){
		$this->_error =  false;
		$this->_results = NULL;
		$this->_count = 0;
	}
	public function query($sql, $params =  array(),$control = null){
		$this->_error = false;
		if($this->_query = $this->_pdo->prepare($sql)) {	
			$x = 1;
			if(count($params)){
				foreach ($params as $key => $param) {
					if ($param == "")
						$this->_query->bindValue(":".$key, null);
					else
						$this->_query->bindValue(":".$key, $param);
					$x++;
				}
			}
				if ($control == 'x') {
					var_dump($this->_query);
					var_dump($params);
					var_dump($this->_query->errorInfo());
					exit();
				}
				$this->_query->execute();

				if ($this->_query->errorInfo()[2] != "") {
					$this->_error = true;					
					$this->_errDesc = $this->_query->errorInfo()[2];
					return $this;
				}
				$this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
				$this->_count = $this->_query->rowCount();
				return $this;

			/*
			if ($this->_query->execute() === true ) {
				$this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
				$this->_count = $this->_query->rowCount();
			}
			else{
				$this->_error = true;
			}*/
		}
		//return $this;
	}
	public function insert($table, $cols)
	{
		end($cols);
		$last = key($cols);
		$positions = "";
		foreach ($cols as $key => $col) {
			$positions = $key==$last? $positions." :$key " : $positions." :$key, ";
		}
		$columns = implode(",",array_keys($cols));
		$sql = "INSERT INTO {$table} ({$columns}) VALUES(".$positions.")";
		if (!$this->query($sql, $cols, $table)->error()) {

			$this->_lastId = $this->_pdo->lastInsertId();
			return $this;
		}
	}
	public function multiInsert($table, $rows = array())
	{
		end($rows);
		$lastRow = key($rows);
		$queryValues = "";
		$valuesArray = array();
		$parametroAbsoluto = 1;
		foreach ($rows as $key => $row) {
			end($row);
			$positions = "";
			$lastCol = key($row);
			foreach ($row as $k => $col) {
				$positions = $k == $lastCol? $positions." :".$k."_".$parametroAbsoluto : $positions." :".$k."_".$parametroAbsoluto.", ";
				$valuesArray[$k."_".$parametroAbsoluto]=$col;
				$parametroAbsoluto++;
			}
			$queryValues = $key == $lastRow? $queryValues." (".$positions.")" : $queryValues." (".$positions."),";
		}
		$columns = implode(", ",array_keys($rows[0]));
		$sql = "INSERT INTO {$table} ({$columns}) VALUES ".$queryValues;
		if (!$this->query($sql, $valuesArray, $table)->error()) {
			return $this;
		}
		return false;
	}
	public function update($table, $where = array(), $fields = array()){
		end($fields);
		$last = key($fields);
		$positions = "";

		foreach ($fields as $key => $col) {
			$positions = $key==$last? $positions." ".$key." = :$key " :$positions." ".$key." = :$key, ";
		}

		$columns = implode(",",array_keys($fields));		
		$whereId = $where['field'];
		$valWhereId = $where['value'];
		$sql = "UPDATE {$table} SET {$positions} WHERE {$whereId} = :whereId ";
		$fields["whereId"] = $valWhereId;
		//$fields["whereId"] = $valWhereId;
		if (!$this->query($sql, $fields, $table)->error()){
			return $this;
		}
		return false;
	}


	public function get($table, $where = array(), $control = null)
	{		
		return $this->action("SELECT *",$table, $where, $control);
	}

	public function action($action, $table, $where = array(), $control = null)
	{
		switch (count($where)) {
			case 3:
				$operators = array('=','>','<','>=','<=', 'like');
				$field = $where[0];
				$operator = $where[1];
				$value = $where[2];
				if (in_array($operator, $operators)) {
					$sql = "{$action} FROM {$table} WHERE {$field} {$operator} ".$value;
					if (!$this->query($sql, [$field =>$value],$table)->error()) {
						return $this;
					}
				}
			break;			
			default:
				$sql = "{$action} FROM {$table}";
				if (!$this->query($sql, array(), $table)->error()) {
					return $this;
				}
			break;
		}
	}
/*
	Metodos
*/
	protected function create($obj){
		$t = $this->getTableName($obj);
		$c = $this->getColumns($obj);
		return $this->_db->insert($t,$c);
	}
	protected function delete($arg)
	{
		$id = "";
		if (is_object($arg)) {
			$cols = $this->getColumns($arg);
			if(in_array("id", $cols))
			{
				$id = $cols["id"];
			}
		}
		$id = $arg;
		$where = array($id, "=", $value);
		return $this->action("DELETE",$table, $where);
	}
/*
*/
protected function getTableName($obj){
	if ($obj){return strtolower(get_class($obj));}
}
protected function getColumns($obj){
	if ($obj){return strtolower(get_object_vars($obj));}
}
/*
*/
public function results()
{
	return $this->_results;
}
public function first()
{
	return $this->results()[0];
}
public function error()
{
	return $this->_error;
}
public function errDesc()
{
	return $this->_errDesc;
}
public function count(){
	return $this->_count;
}
public function lastId(){
	return $this->_lastId;
}
}