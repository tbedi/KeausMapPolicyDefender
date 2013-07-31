<?php

/**
     * NOTICE OF LICENSE
     *
     * This source file is subject to the Open Software License (OSL 3.0)
     * that is bundled with this package in the file LICENSE.txt.
     * It is also available through the world-wide-web at this URL:
     * http://opensource.org/licenses/osl-3.0.php
     * If you did not receive a copy of the license and are unable to
     * obtain it through the world-wide-web, please send an email
     * to customerservice@kraususa.com so we can send you a copy immediately.
     *
     * @author Alex Lukyanov
     * @copyright   Copyright (c) 2013 Kraus USA. (http://www.kraususa.com)
     * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
     * Created: Jun 7, 2013
     *
     */
class DB {
	protected $db_info = array("host" => "192.168.1.74", "db_name" => "prices", "username" => "india", "password" => "indiaICG2013");	 		                                
	public   $db_handle;  
	
	 public  function __construct() {
		if (!$this->db_handle) {
		 	$this->getConnection();
		}
	}
	
	
	protected function getConnection(){		
		$this->db_handle = new PDO("mysql:host={$this->db_info['host']};dbname={$this->db_info['db_name']}", $this->db_info['username'], $this->db_info['password'], array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
		$this->db_handle->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$this->db_handle->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
		$this->db_handle->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);			 
	}
	
	public function GetResultObj($sql){
		$result_obj_array=array();  
		$result = $this->db_handle->query($sql) or die(mysql_error());		 
		while($row = $result->fetchObject()){				 	
			array_push($result_obj_array, $row);
		}
		return $result_obj_array;
	}
	
	public function InsertObj($table,$objects){
		$query_result=$this->db_handle->query(" SELECT * FROM ".$table." LIMIT 1");		 		 		 
		$insert_data=array();
		
		foreach ($objects as $object) {
			$data=array();
			for  (  $i=1; $i< $query_result->columnCount();$i++) {
				$column=$query_result->getColumnMeta($i);				 
				/*$data[$column['name']]=($column['name']=='created_at' ? "NOW()": '"'.$object->$column['name'].'"');*/
				$data[$column['name']]='"'.$object->$column['name'].'"';
			}
			$fields=implode(",", array_keys($data));
			array_push($insert_data,"(".implode(',', $data).")");
		}
		
		$sql = "INSERT INTO ".$table." (" . $fields . ") VALUES " . implode(',',$insert_data);
		$this->db_handle->beginTransaction();
		$stmt = $this->db_handle->prepare ($sql);
		try {
			$stmt->execute();
		} catch (PDOException $e){
			echo $e->getMessage();
		}
		$last_insert_id=$this->db_handle->lastInsertId();
		$this->db_handle->commit();	
		
		return 	$last_insert_id;
	}
		 
	
	public function InsertUniqOnlyObj($table,$objects, $keyfield=""){
		 
		$uniq_objects=array();
	 
		foreach ($objects as $object) {
			$data=array();
			
			if ($keyfield){
				$where=" WHERE ".$keyfield." = \"".$object->$keyfield."\"";
			}else {
				$where="";
			}
			 
			$query_result=$this->db_handle->query(" SELECT id FROM ".$table." ".$where);
			$existing_object=$query_result->fetchObject();
			  
			 
			if (!$existing_object) {
				array_push($uniq_objects,$object);		
				 
			}else {
				$last_insert_id=$existing_object->id;
			}
		}
		if (count($uniq_objects))
			$last_insert_id=$this->InsertObj($table,$uniq_objects);
 
		return  $last_insert_id;
	}

	public function ExecSql($sql){
		$this->db_handle->query($sql) or die(mysql_error());	
	}
	
}

?>
 