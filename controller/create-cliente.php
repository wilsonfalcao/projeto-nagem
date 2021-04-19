<?php


/* CONTROLLER DE DADOS REMETIDOS AO BANCO DIRETAMENTE DO SITE 

(1) Resrição de Acesso por Método POST

*/

//Validando informações de entrada
$nome   =  trim(strip_tags($_POST["nome"]));
$cnpj   =  trim(strip_tags($_POST["cnpj"]));
$status   =  trim(strip_tags($_POST["status"]));

//Validando informações de entrada
if(!is_numeric($status) && !is_numeric($cnpj)){
    header("HTTP/1.1 301 Moved Permanently"); 
    header("Location: /"); 
    exit();
}

if(!isset($nome) && !isset($cnpj) ){
    header("HTTP/1.1 301 Moved Permanently"); 
    header("Location: /"); 
    exit();
}

include dirname(__FILE__,2).'/model/databaseModel.php';
$model = new databaseModel();

$dataToParse = ["nome"=>$nome,
                "cnpj"=>$cnpj,
                "status"=>$status];
$result = $model->InsertClientes($dataToParse);


if($result){
    header("HTTP/1.1 200 OK");
}else{
    header("HTTP/1.1 404 ERROR");
}


?>