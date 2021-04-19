<?php

/* CONTROLLER DE DADOS REMETIDOS AO BANCO DIRETAMENTE DO SITE 

(1) Resrição de Acesso por Método POST

*/

//Validando informações de entrada
$id   =  trim(strip_tags($_POST["id"]));
$table   =  trim(strip_tags($_POST["type"]));

//Validando informações de entrada
if(!is_numeric($id) && !is_numeric($table)){
    header("HTTP/1.1 301 Moved Permanently"); 
    header("Location: /"); 
    exit();
}

if(!isset($table) && !isset($id) ){
    header("HTTP/1.1 301 Moved Permanently"); 
    header("Location: /"); 
    exit();
}

include dirname(__FILE__,2).'/model/databaseModel.php';
$model = new databaseModel();


switch($table){
    case 1:$result = $model->DeleteCliente($id); break;
    case 2:$result = $model->DeleteContato($id); break;
}


header("HTTP/1.1 200 OK");
header('Content-Type: application/json');


?>