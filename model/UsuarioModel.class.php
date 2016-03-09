<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UsuarioModel
 *
 * @author Hellen
 */

class UsuarioModel {

    private $conexao;
    private $id;
    private $nome;
    private $email;
    private $senha;
    private $tipo;
    private $ativo;

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getNome() {
        return $this->nome;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getSenha() {
        return $this->senha;
    }

    public function getTipo() {
        return $this->tipo;
    }

    public function getAtivo() {
        return $this->ativo;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setSenha($senha) {
        $this->senha = $senha;
    }

    public function setTipo($tipo) {
        $this->tipo = $tipo;
    }

    public function setAtivo($ativo) {
        $this->ativo = $ativo;
    }

    function __construct() {
        try {
            $this->conexao = new PDO("mysql:host=127.0.0.1;dbname=celestial", "root", "");
            $this->conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Erro: " . $e->getMessage();
        }
    }

    public function consultar() {
        try {
            $sql = "SELECT * FROM usuario ORDER BY nome ASC";
            $query = $this->conexao->prepare($sql);
            $query->execute();
            return $query;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function gravar($idUsuario = NULL) {
        try {
            if ($idUsuario == NULL) {
                $sql = "INSERT INTO usuario(nome, email, senha, tipo, ativo) 
                    VALUES(:nome, :email, :senha, :tipo, :ativo)";
            } else {
                $sql = "UPDATE usuario SET nome = :nome, email = :email, senha = :senha, tipo = :tipo, ativo = :ativo WHERE id = :idUsuario";
            }
            $query = $this->conexao->prepare($sql);
            $query->bindValue(":nome", $this->nome);
            $query->bindValue(":email", $this->email);
            $query->bindValue(":senha", $this->senha);
            $query->bindValue(":tipo", $this->tipo);
            $query->bindValue(":ativo", $this->ativo);
            if ($idUsuario != NULL) {
                $query->bindValue(":idUsuario", $idUsuario);
            }
            $query->execute();
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function deletar($id) {
        try {
            $sql = "DELETE FROM usuario WHERE id = :id";
            $query = $this->conexao->prepare($sql);
            $query->bindValue(":id", $id);
            $query->execute();
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function validaLogin($email, $senha) {
        try {
            $sql = "SELECT * FROM usuario WHERE email= :email AND senha = :senha";
            $query = $this->conexao->prepare($sql);
            $query->bindValue(":email", $email);
            $query->bindValue(":senha", $senha);
            $query->execute();
            if ($query->rowCount() > 0) {
                $registro = $query->fetch(PDO::FETCH_ASSOC);
                $this->nome = $registro["nome"];
                $this->tipo = $registro["tipo"];
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            echo "Erro ao validar login " . $e->getMessage();
        }
    }

    public function consultarIdPorNome($nome) {
        try {
            $sql = "SELECT id FROM usuario WHERE nome = :nome";
            $query = $this->conexao->prepare($sql);
            $query->bindValue(":nome", $nome);
            $query->execute();

            $registro = $query->fetch(PDO::FETCH_ASSOC);
            $this->id = $registro["id"];
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function consultarPorId($idUsuario) {
        try {
            $sql = "SELECT * FROM usuario WHERE id = :idUsuario";
            $query = $this->conexao->prepare($sql);
            $query->bindValue(":idUsuario", $idUsuario);
            $query->execute();

            $resultado = $query->fetch(PDO::FETCH_ASSOC);
            $this->nome = $resultado["nome"];
            $this->email = $resultado["email"];
            $this->senha = $resultado["senha"];
            $this->tipo = $resultado["tipo"];
            $this->ativo = $resultado["ativo"];
            return true;
        } catch (PDOException $e) {
            echo "Erro ao consultar usuário " . $e->getMessage();
            return false;
        }
    }

}
