<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "model/databaseModel.php";

$model = new databaseModel();

$id = $_GET["id"];
$type = $_GET["atualizar"];

$contatosData = null;

if($type == "clientes"){
    $contatosData = $model->GetClientes($id);
}

if($type == "contatos"){
    $contatosData = $model->GetContatos($id);
}

if(!isset($contatosData)){
    header("HTTP/1.1 301 Moved Permanently"); 
    header("Location: /"); 
    exit();
}


?>


<html>
    <head>

        <style>
            th {
            text-align: center;
            }

            .glyphicon {
            margin-right: 10px;
            }

            .crud-table {
            max-width: 800px;
            padding: 40px 0;
            }

            .form-alert {
            margin-top: 10px;
            }

            .form-inline {
            margin-bottom: 20px;
            }
        </style>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css"/>
    </head>
    <body>
      <div class="container crud-table" ng-app="myApp" ng-controller="namesCtrl">
        <div class="crude-form__wrapper">
            <h1>Atualizar Dados</h1>
            <?php if($type=="contatos"):?>
                <form id="formcontatos">
                    <div class="form-group">
                        <label for="salary">Associar ao Cliente</label>
                        <select class="form-control" name="idcliente" require>
                            <option value="<?php echo $contatosData[0]["idcliente"];?>"><?php echo $contatosData[0]["nomempresa"];?></option>
                            <?php foreach($model->GetAllEmpresas() as $data):?>
                            <option value="<?php echo $data["idcliente"];?>"><?php echo $data["nome"];?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="name">Nome</label>
                        <input class="form-control" type="text" value="<?php echo $contatosData[0]["nomecontato"];?>" name="nomecontato" placeholder="Nome completo" required="required"/>
                    </div>
                    <div class="form-group">
                        <label for="country">Email</label>
                        <input class="form-control" type="email" value="<?php echo $contatosData[0]["emailcontato"];?>" name="emailcontato" placeholder="email@dominio.com" required="required"/>
                    </div>
                    <div class="form-group">
                        <label for="country">CPF</label>
                        <input class="form-control" type="number" value="<?php echo $contatosData[0]["cpfcontato"];?>" name="cpfcontato" placeholder="(Apenas Números)" required="required"/>
                    </div>
                        <input class="form-control" type="hidden" value="<?php echo $contatosData[0]["idcontato"];?>" name="idcontato"/>
                    <button class="btn btn-primary"> <i class="glyphicon glyphicon-pencil"> </i>Savar</button>
                    <button onclick="window.open('/','_self');" class="btn btn-secondary">Voltar</button>
                </form>
            <?php endif;?>
            <?php if($type=="clientes"):?>
                <form id="formcliente">
                    <div class="form-group">
                    <label for="name">Nome (Razão Social)</label>
                    <input class="form-control" type="text" value="<?php echo $contatosData[0]["nome"];?>" name="nome" placeholder="Nome da Empresa" required="required"/>
                    </div>
                    <div class="form-group">
                    <label for="country">CNPJ</label>
                    <input class="form-control" type="number" value="<?php echo $contatosData[0]["cnpj"];?>" name="cnpj" placeholder="CNPJ (Apenas número)" required="required"/>
                    </div>
                    <div class="form-group">
                    <label for="salary">Status</label>
                    <select class="form-control" name="status" require>
                        <option value="1">Ativo</option>
                        <option value="0">Inativo</option>
                    </select>
                    </div>
                    <button class="btn btn-primary"> <i class="glyphicon glyphicon-pencil"> </i>Savar</button>
                    <button onclick="window.open('/','_self');" class="btn btn-secondary">Voltar</button>
                    <input class="form-control" type="hidden" value="<?php echo $contatosData[0]["idcliente"];?>" name="idcliente"/>
                </form>
            <?php endif;?>
          </div>
      </div>
    </body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script>

      function deletar(typedata,idpost){
        $.post( "controller/delete-data.php", {type: typedata , id:idpost });
        location.reload();
      }

    </script>

    <script>
        
        $(document).ready(function(){

            $("#formcontatos").submit(function(){
              $.ajax({
                  url: 'controller/update-contato.php',
                  data:$("#formcontatos").serialize() ,
                  type: 'POST',
                  success: function ( data ) {
                    location.reload();
                  },
                 error:function(data){
                   console.log(data);
                 }
              });
              return false;
            });

            $("#formcliente").submit(function(){
              $.ajax({
                  url: 'controller/update-clientes.php',
                  data:$("#formcliente").serialize() ,
                  type: 'POST',
                  success: function ( data ) {
                    location.reload();
                  },
                 error:function(data){
                   console.log(data);
                 }
              });
              return false;
            });

        });
    </script>
</html>