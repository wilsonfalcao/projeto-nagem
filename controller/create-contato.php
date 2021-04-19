<?php

/* CONTROLLER DE DADOS REMETIDOS AO BANCO DIRETAMENTE DO SITE 

(1) Resrição de Acesso por Método POST

*/

//Validando informações de entrada
$idcliente   =  trim(strip_tags($_POST["idcliente"]));
$nomecontato   =  trim(strip_tags($_POST["nomecontato"]));
$emailcontato   =  trim(strip_tags($_POST["emailcontato"]));
$cpfcontato   =  trim(strip_tags($_POST["cpfcontato"]));

//Validando informações de entrada
if(!is_numeric($cpfcontato) && !is_numeric($idcliente)){
    header("HTTP/1.1 301 Moved Permanently"); 
    header("Location: /"); 
    exit();
}

if(!isset($nomecontato) && !isset($emailcontato) ){
    header("HTTP/1.1 301 Moved Permanently"); 
    header("Location: /"); 
    exit();
}

include dirname(__FILE__,2).'/model/databaseModel.php';
$model = new databaseModel();

$dataToParse = ["idcliente"=>$idcliente,
                "nomecontato"=>$nomecontato,
                "emailcontato"=>$emailcontato,
                "cpfcontato"=>$cpfcontato];

$result = $model->InsertContatos($dataToParse);

if($result){
    header("HTTP/1.1 200 OK");
}else{
    header("HTTP/1.1 404 ERROR");
}


?>