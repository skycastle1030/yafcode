<?php

class DES_Basic {  

    function encrypt($str) { 
       return $this->doGet('http://127.0.0.1:7809/?mobile=' . $str);
    }


	private  function doGet($url){
        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_URL, $url); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); 
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET'); 
        curl_setopt($ch,CURLOPT_HTTPHEADER,array("X-HTTP-Method-Override: GET"));
        $document = curl_exec($ch);
        curl_close($ch);
        return $document;
    }
 
}  
