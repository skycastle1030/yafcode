<?php

class SampleModel 
{
    public function __construct() 
	{
		
    }   
    
    public function selectSample() 
	{
	    $pdo = PDO_Demo::getInstance();
	    $id= $pdo->insert('roles',['name'=>'rules56','remark'=>'df77jaskdjfdfdfdsdfs231','order'=>10,'status'=>5]);
        return $id;
    }

    public function insertSample($arrInfo) 
	{
        return true;
    }
}
