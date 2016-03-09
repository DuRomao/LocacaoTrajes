<?php

class CategoriaModel {

    private $conexao;
    private $nome;

    public function getNome() {
        return $this->nome;
    }

    public function setNome($nome) {
        $this->nome = $nome;
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
            $sql = "SELECT * FROM categoria ORDER BY nome ASC";
            $query = $this->conexao->prepare($sql);
            $query->execute();
            return $query;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function consultarPorId($idCategoria) {
        try {
            $sql = "SELECT * FROM categoria WHERE id = :idCategoria";
            $query = $this->conexao->prepare($sql);
            $query->bindValue(":idCategoria", $idCategoria);
            $query->execute();

            $resultado = $query->fetch(PDO::FETCH_ASSOC);
            $this->nome = $resultado["nome"];
            return true;
        } catch (PDOException $e) {
            echo "Erro ao consultar categoria " . $e->getMessage();
            return false;
        }
    }

    public function gravar($idCategoria = NULL) {
        try {

            if ($idCategoria == NULL) {
                $sql = "INSERT INTO categoria(nome) 
                    VALUES(:nome)";
            } else {
                $sql = "UPDATE categoria SET nome = :nome WHERE id = :idCategoria";
            }
            $query = $this->conexao->prepare($sql);

            $query->bindValue(":nome", $this->nome);
            if ($idCategoria != NULL) {
                $query->bindValue(":idCategoria", $idCategoria);
            }
            $query->execute();
            return true;
        } catch (PDOException $e) {
            echo $e;
            return false;
        }
    }

    public function deletar($id) {
        try {
            $sql = "DELETE FROM categoria WHERE id = :id";
            $query = $this->conexao->prepare($sql);
            $query->bindValue(":id", $id);
            $query->execute();
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
    
    public function pesquisarTermo($termo){
        try {
            $sql = "SELECT * FROM categoria WHERE nome LIKE '%{$termo}%' ORDER BY nome ASC";
            $query = $this->conexao->prepare($sql);
            $query->execute();
            return $query;
        } catch (PDOException $e) {
            echo "Erro ao consultar categoria " . $e->getMessage();
            return false;
        }
    }

}
