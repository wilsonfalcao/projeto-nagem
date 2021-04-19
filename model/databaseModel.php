<?php

include dirname(__FILE__,2)."/constants.php";

class databaseModel {

    private $link;

    function __construct(){

        //Verificando a extensão do mysqli em sistema
        if (!function_exists('mysqli_connect') && !extension_loaded('mysqli')) {
            echo 'Falta o arquivo em sistema!!!';
            exit;
        }

        $this->link = new mysqli(constant("HOST"), constant("USER"), constant("PASSWORD"), constant("DATABASE"));

        if($this->link->connect_errno){
            echo "Problemas para conectar com o banco";
            exit;
        }
    }

    //Encapsulamento de Métodos da Classe
    public function GetClientes($id){
        if(isset($id)){
            return $this->get_clientesID($id);
        }else{
            return $this->get_clientes();
        }
    }
    public function GetContatos($id){
        if(isset($id)){
            return $this->get_contatosID($id);
        }else{
            return $this->get_contatos();
        }
    }
    public function InsertClientes($ArrayInsert_){
        return $this->insert_clientes($ArrayInsert_);
    }
    public function InsertContatos($ArrayContatos_){
        return $this->insert_contatos($ArrayContatos_);
    }
    public function UpdateContatos($ArrayContatos_){
        return $this->update_contatos($ArrayContatos_);
    }
    public function UpdateClientes($ArrayContatos_){
        return $this->update_clientes($ArrayContatos_);
    }
    public function DeleteCliente($id){
        return $this->delete_cliente($id);

    }
    public function DeleteContato($id){
        return $this->delete_contato($id);
    }
    public function GetAllEmpresas(){
        return $this->get_allcompanies();
    }

    // Utilizando a extensão MySQLI para funções básica de consulta e registros
    protected function get_clientes(){
        try{
            $result = $this->link->prepare("select id_clientes, nome, cnpj, status,if(status=1, 'Ativo', 'Inativo') as status1, registro from clientes;");
            $result->execute();

            $rows = [];
            foreach($result->get_result() as $row){
                array_push($rows,["idcliente"=>$row["id_clientes"],
                "nome"=>$row["nome"],
                "cnpj"=>$row["cnpj"],
                "status"=>$row["status"],
                "status1"=>$row["status1"],
                "registro"=>$row["registro"]]);
            }
            
            if($result->error){
                throw new Exception();
            }

        }catch(Exception $e){
            return $result->error;
        }finally{
            $result->close();
        }
        return $rows;
    }
    protected function get_contatos(){
        try{
            $result = $this->link->prepare("select a.id_contato, a.id_cliente, b.nome, a.nome_contato, a.email_contato, a.cpf, a.registro
            from contatos a, clientes b  
            where a.id_cliente = b.id_clientes;");

            $result->execute();

            $rows = [];
            foreach($result->get_result() as $row){
                array_push($rows,["idcontato"=>$row["id_contato"],
                "idcliente"=>$row["id_cliente"],
                "nomempresa"=>$row["nome"],
                "nomecontato"=>$row["nome_contato"],
                "emailcontato"=>$row["email_contato"],
                "cpfcontato"=>$row["cpf"],
                "dataregistro"=>$row["registro"]]);
            }
            
            if($result->error){
                throw new Exception();
            }

        }catch(Exception $e){
            return $result->error;
        }finally{
            $result->close();
        }
        return $rows;
    }
    protected function get_clientesID($id){
        try{
            $result = $this->link->prepare("select id_clientes, nome, cnpj, status, registro 
            from clientes where id_clientes = ?");

            $result->bind_param('i', $id);

            $result->execute();

            if($this->link->error){
                return $result->error;
            }
            $rows = [];
            foreach($result->get_result() as $row){
                array_push($rows,["idcliente"=>$row["id_clientes"],
                "nome"=>$row["nome"],
                "cnpj"=>$row["cnpj"],
                "status"=>$row["status"],
                "registro"=>$row["registro"]]);
            } 

        }catch(Exception $e){
            return $result->error;
        }finally{
            $result->close();
        }
        return $rows;
    }
    protected function get_contatosID($id){
        try{
            $result = $this->link->prepare("select a.id_contato, a.id_cliente, b.nome, a.nome_contato, a.email_contato, a.cpf, a.registro
            from contatos a, clientes b  
            where a.id_contato = ? and a.id_cliente = b.id_clientes;");

            $result->bind_param('i', $id);

            $result->execute();

            if($result->error){
                throw new Exception();
            }
            
            $rows = [];
            foreach($result->get_result() as $row){
                array_push($rows,["idcontato"=>$row["id_contato"],
                "idcliente"=>$row["id_cliente"],
                "nomempresa"=>$row["nome"],
                "nomecontato"=>$row["nome_contato"],
                "emailcontato"=>$row["email_contato"],
                "cpfcontato"=>$row["cpf"],
                "dataregistro"=>$row["registro"]]);
            }

        }catch(Exception $e){
            return $result->error;
        }finally{
            $result->close();
        }
        return $rows;
    }
    protected function insert_clientes($ArrayInsert){

        try{
            $query = "INSERT INTO `clientes` (`nome`, `cnpj`, `status`) VALUES (?,?,?);";

            $response = $this->link->prepare($query);

            $response->bind_param('sii', $ArrayInsert["nome"],$ArrayInsert["cnpj"],$ArrayInsert["status"]);

            $response->execute();

            if($response->error){
                throw new Exception();
            }

        }catch(Exception $e){
            return $response->error;
        }finally{
            $this->link->close();
        }
        return $response;
    }
    protected function insert_contatos($Data){

        try{
            $query = "INSERT INTO `contatos` (`id_cliente`, `nome_contato`, `email_contato`, `cpf`) VALUES (?,?,?,?)";

            $response = $this->link->prepare($query);

            $response->bind_param('issi', $Data["idcliente"], $Data["nomecontato"], $Data["emailcontato"], $Data["cpfcontato"]);

            $response->execute();

            if($response->error){
                throw new Exception();
            }

        }catch(Exception $e){
            return $response->error;
        }finally{
            $this->link->close();
        }
        return $response;
    }
    protected function get_allcompanies(){

        try{
            $result = $this->link->prepare("select nome,id_clientes from clientes;");

            $result->execute();

            $rows = [];
            foreach($result->get_result() as $row){
                array_push($rows,["nome"=>$row["nome"],
                "idcliente"=>$row["id_clientes"]]);
            }

            if($result->error){
                throw new Exception();
            }

        }catch(Exception $e){
            return $result->error;
        }finally{
            $result->close();
        }
        return $rows;

    }

    //Atualização de dados
    protected function update_clientes($data){

        try{
            $query = "UPDATE `clientes` SET `nome` = '".$data["nome"]."', `cnpj` = '".$data["cnpj"]."',
             `status` = '".$data["status"]."' WHERE `clientes`.`id_clientes` = '".$data["idcliente"]."';";
            $response = $this->link->query($query);
            if($result->error){
                throw new Exception();
            }
        }catch(Exception $e){
            return $result->error;
        }finally{
            $this->link->close();
        }
        return $response;

    }
    protected function update_contatos($data){

        try{
            $query = "UPDATE `contatos` SET `id_cliente` = '".$data["idcliente"]."', `nome_contato` = '".$data["nomecontato"]."', 
            `email_contato` = '".$data["emailcontato"]."', `cpf` = '".$data["cpfcontato"]."' WHERE `contatos`.`id_contato` = ".$data["idcontato"].";";

            $response = $this->link->query($query);

            if($response->error){
                throw new Exception();
            }
        }catch(Exception $e){
            return $result->error;
        }finally{
            $this->link->close();
        }
        return $response;
        
    }

    //Exclusão de dados do banco
    protected function delete_contato($id){
        try{
            $response = $this->link->prepare("DELETE FROM contatos where id_contato = ?");

            $response->bind_param('i', $id);

            $response->execute();

        }catch(Exception $e){
            return false;
        }finally{
            $this->link->close();
        }
        return true;
    }
    protected function delete_cliente($id){

        try{
            $query = "DELETE FROM clientes where id_clientes = ".$id.";DELETE FROM contatos where id_cliente = ".$id;
            $response = $this->link->multi_query($query);

        }catch(Exception $e){
            return false;
        }finally{
            $this->link->close();
        }
        return true;
        
    }
    



}