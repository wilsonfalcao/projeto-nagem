<?php

include "model/databaseModel.php";

$model = new databaseModel();

$contatosData = $model->GetContatos(null);
$clientesData = $model->GetClientes(null);

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
        <div class="clearfix">
          <div class="form-inline pull-left">
            <button class="btn btn-success"><span class="glyphicon glyphicon-plus"> </span>Add Cliente</button>
            <button class="btn btn-info"><span class="glyphicon glyphicon-plus"> </span>Add Contato</button>
          </div>
        </div>

        <table id="selectclientes" class="table table-striped">
          <thead>
            <tr>
              <th>Razão Social</th>
              <th>CNPJ</th>
              <th>Status</th>
              <th>Data</th>
            </tr>
          </thead>
          <tbody class="text-center">
              <?php foreach($clientesData as $data => $value):?>
                <tr>
                  <td><?php echo $value["nome"];?></td>
                  <td><?php echo $value["cnpj"];?></td>
                  <td><?php echo $value["status"];?></td>
                  <td><?php echo $value["registro"];?></td>
                  <td>
                  <button onclick="editar('clientes',<?php echo $value['idcliente'];?>)" class="btn btn-primary">Editar</button>
                  </td>
                  <td> 
                  <button onclick="deletar(1,<?php echo $value['idcliente'];?>)" class="btn btn-danger">Deletar</button>
                  </td>
                </tr>
              <?php endforeach;?>
          </tbody>
        </table>
        <table id="selectcontatos" style="display: none;" class="table table-striped">
            <thead>
              <tr>
                <th>Usuário</th>
                <th>Email</th>
                <th>CPF</th>
                <th>Cliente</th>
                <th></th>
              </tr>
            </thead>
            <tbody class="text-center">
              <?php foreach($contatosData as $data => $value):?>
                <tr>
                  <td><?php echo $value["nomecontato"];?></td>
                  <td><?php echo $value["emailcontato"];?></td>
                  <td><?php echo $value["cpfcontato"];?></td>
                  <td><?php echo $value["nomempresa"];?></td>
                  <td>
                    <button onclick="editar('contatos',<?php echo $value['idcontato'];?>)" class="btn btn-primary">Editar</button>
                  </td>
                  <td> 
                    <button onclick="deletar(2,<?php echo $value['idcontato'];?>)" class="btn btn-danger">Deletar</button>
                  </td>
                </tr>
              <?php endforeach;?>
            </tbody>
          </table>
          <!--Inclusão de dados cliente-->
        
          <div id="clientes" style="display: none;" class="crude-form__wrapper">
          <h1>Adicionar Clientes</h1>
          <h3 ng-show="editForm">Editar</h3>
          <h3 ng-show="addForm">Adicionar</h3>
          <form id="formcliente">
            <div class="form-group">
              <label for="name">Nome (Razão Social)</label>
              <input class="form-control" type="text" name="nome" placeholder="Nome da Empresa" required="required"/>
            </div>
            <div class="form-group">
              <label for="country">CNPJ</label>
              <input class="form-control" type="number" name="cnpj" placeholder="CNPJ (Apenas número)" required="required"/>
            </div>
            <div class="form-group">
              <label for="salary">Status</label>
              <select class="form-control" name="status" require>
                <option value="1">Ativo</option>
                <option value="0">Inativo</option>
              </select>
            </div>
            <button class="btn btn-primary"> <i class="glyphicon glyphicon-pencil"> </i>Savar</button>
          </form>
        </div>


        <!--Inclusão de dados contato-->
        <div id="contatos" style="display: none;" class="crude-form__wrapper">
            <h1>Adicionar Contatos</h1>
            <form id="formcontatos">
            <div class="form-group">
              <label for="salary">Associar ao Cliente</label>
              <select class="form-control" name="idcliente" require>
                <?php foreach($clientesData as $data):?>
                  <option value="<?php echo $data["idcliente"];?>"><?php echo $data["nome"];?></option>
                <?php endforeach;?>
              </select>
            </div>
            <div class="form-group">
              <label for="name">Nome</label>
              <input class="form-control" type="text" name="nomecontato" placeholder="Nome completo" required="required"/>
            </div>
            <div class="form-group">
              <label for="country">Email</label>
              <input class="form-control" type="email" name="emailcontato" placeholder="email@dominio.com" required="required"/>
            </div>
            <div class="form-group">
              <label for="country">CPF</label>
              <input class="form-control" type="number" name="cpfcontato" placeholder="(Apenas Números)" required="required"/>
            </div>
            <button class="btn btn-primary"> <i class="glyphicon glyphicon-pencil"> </i>Savar</button>
          </form>
          </div>
      </div>
    </body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script>

      function deletar(typedata,idpost){
        $.post( "controller/delete-data.php", {type: typedata , id:idpost });
        location.reload();
      }

      function editar(type,id){
        window.open(window.location.href+'update.php?id='+id+'&atualizar='+type,"_self");
      }

    </script>

    <script>
        
        $(document).ready(function(){
            
           $(".btn-success").click(function(){
            
            $("#contatos").hide();
            $("#selectclientes").show();
            $("#selectcontatos").hide();
            ($("#clientes").is(":visible")) ? $("#clientes").hide() : $("#clientes").show();

           });

           $(".btn-info").click(function(){
            
            $("#clientes").hide();
            $("#selectclientes").hide();
            $("#selectcontatos").show();
            ($("#contatos").is(":visible")) ? $("#contatos").hide() : $("#contatos").show();

            });

            $("#formcliente").submit(function(){
              $.ajax({
                  url: 'controller/create-cliente.php',
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

            $("#formcontatos").submit(function(){
              $.ajax({
                  url: 'controller/create-contato.php',
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

        });
    </script>
</html>