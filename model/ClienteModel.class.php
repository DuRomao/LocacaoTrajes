<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ClienteModel
 *
 * @author Hellen
 */
class ClienteModel {

    private $conexao;
    private $idCliente;
    private $nome;
    private $email;
    private $rg;
    private $cpf;
    private $cep;
    private $rua;
    private $numero;
    private $bairro;
    private $telefone;
    private $celular;

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getIdCliente() {
        return $this->idCliente;
    }

    public function getNome() {
        return $this->nome;
    }

    public function getRg() {
        return $this->rg;
    }

    public function getCpf() {
        return $this->cpf;
    }

    public function getCep() {
        return $this->cep;
    }

    public function getRua() {
        return $this->rua;
    }

    public function getNumero() {
        return $this->numero;
    }

    public function getBairro() {
        return $this->bairro;
    }

    public function getTelefone() {
        return $this->telefone;
    }

    public function getCelular() {
        return $this->celular;
    }

    public function setIdCliente($idCliente) {
        $this->idCliente = $idCliente;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function setRg($rg) {
        $this->rg = $rg;
    }

    public function setCpf($cpf) {
        $this->cpf = $cpf;
    }

    public function setCep($cep) {
        $this->cep = $cep;
    }

    public function setRua($rua) {
        $this->rua = $rua;
    }

    public function setNumero($numero) {
        $this->numero = $numero;
    }

    public function setBairro($bairro) {
        $this->bairro = $bairro;
    }

    public function setTelefone($telefone) {
        $this->telefone = $telefone;
    }

    public function setCelular($celular) {
        $this->celular = $celular;
    }

    function __construct() {
        try {
            $this->conexao = new PDO("mysql:host=127.0.0.1;dbname=celestial", "root", "");
            $this->conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Erro: " . $e->getMessage();
        }
    }

    public function gravar($idCliente = NULL) {
        try {
            if ($idCliente == NULL) {
                $sql = "INSERT INTO cliente(nome, email, rg, cpf, cep, rua, numero, bairro, telefone, celular)
                    VALUES(:nome, :email, :rg, :cpf, :cep, :rua, :numero, :bairro, :telefone, :celular)";
            } else {
                $sql = "UPDATE cliente SET nome = :nome, email = :email, rg = :rg, cpf = :cpf, cep = :cep, rua = :rua, numero = :numero, bairro = :bairro, telefone = :telefone, celular = :celular WHERE id = :idCliente";
            }
            $query = $this->conexao->prepare($sql);
            $query->bindValue(":nome", $this->nome);
            $query->bindValue(":email", $this->email);
            $query->bindValue(":rg", $this->rg);
            $query->bindValue(":cpf", $this->cpf);
            $query->bindValue(":cep", $this->cep);
            $query->bindValue(":rua", $this->rua);
            $query->bindValue(":numero", $this->numero);
            $query->bindValue(":bairro", $this->bairro);
            $query->bindValue(":telefone", $this->telefone);
            $query->bindValue(":celular", $this->celular);
            if ($idCliente != NULL) {
                $query->bindValue(":idCliente", $idCliente);
            }
            $query->execute();

            return true;
        } catch (PDOException $e) {
            echo "erro" . $e->getMessage();
            return false;
        }
    }

    public function consultar() {
        try {
            $sql = "SELECT * FROM cliente ORDER BY nome ASC";
            $query = $this->conexao->prepare($sql);
            $query->execute();
            return $query;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function deletar($id) {
        try {
            $sql = "DELETE FROM cliente WHERE id = :id";
            $query = $this->conexao->prepare($sql);
            $query->bindValue(":id", $id);
            $query->execute();
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function consultarPorId($idCliente) {
        try {
            $sql = "SELECT * FROM cliente WHERE id = :idCliente";
            $query = $this->conexao->prepare($sql);
            $query->bindValue(":idCliente", $idCliente);
            $query->execute();
            $resultado = $query->fetch(PDO::FETCH_ASSOC);
            $this->nome = $resultado["nome"];
            $this->email = $resultado["email"];
            $this->cpf = $resultado["cpf"];
            $this->rg = $resultado["rg"];
            $this->rua = $resultado["rua"];
            $this->numero = $resultado["numero"];
            $this->bairro = $resultado ["bairro"];
            $this->cep = $resultado["cep"];
            $this->telefone = $resultado["telefone"];
            $this->celular = $resultado["celular"];

            return true;
        } catch (PDOException $e) {
            echo "Erro ao consultar cliente " . $e->getMessage();
            return false;
        }
    }
    
    public function pesquisarTermo($termo){
        try {
            $sql = "SELECT * FROM cliente WHERE nome LIKE '%{$termo}%' ORDER BY nome ASC";
            $query = $this->conexao->prepare($sql);
            $query->execute();
            return $query;
        } catch (PDOException $e) {
            echo "Erro ao consultar cliente " . $e->getMessage();
            return false;
        }
    }

}
