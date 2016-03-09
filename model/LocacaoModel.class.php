<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LocacaoModel
 *
 * @author Hellen
 */
class LocacaoModel {

    private $conexao;
    private $id;
    private $descricao;
    private $valor_total;
    private $dia_locacao;
    private $dia_entrega;
    private $usuario_id;
    private $cliente_id;
    private $produtos;
    private $ativo;

    public function getId() {
        return $this->id;
    }

    public function getDescricao() {
        return $this->descricao;
    }

    public function getValor_total() {
        return $this->valor_total;
    }

    public function getDia_locacao() {
        return $this->dia_locacao;
    }

    public function getDia_entrega() {
        return $this->dia_entrega;
    }

    public function getUsuario_id() {
        return $this->usuario_id;
    }

    public function getCliente_id() {
        return $this->cliente_id;
    }

    public function setDescricao($descricao) {
        $this->descricao = $descricao;
    }

    public function setValor_total($valor_total) {
        $this->valor_total = $valor_total;
    }

    public function setDia_locacao($dia_locacao) {
        $this->dia_locacao = $dia_locacao;
    }

    public function setDia_entrega($dia_entrega) {
        $this->dia_entrega = $dia_entrega;
    }

    public function setUsuario_id($usuario_id) {
        $this->usuario_id = $usuario_id;
    }

    public function setCliente_id($cliente_id) {
        $this->cliente_id = $cliente_id;
    }

    public function getAtivo() {
        return $this->ativo;
    }

    public function setAtivo($ativo) {
        $this->ativo = $ativo;
    }

    public function getProdutos() {
        return $this->produtos;
    }

    public function setProdutos($produtos) {
        $this->produtos = $produtos;
    }

    function __construct() {
        try {
            $this->conexao = new PDO("mysql:host=127.0.0.1;dbname=celestial", "root", "");
            $this->conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Erro: " . $e->getMessage();
        }
    }

    public function consultar($status) {
        try {
            $sql = "SELECT l.*, c.nome as cliente FROM locacao l INNER JOIN cliente c ON l.cliente_id = c.id WHERE ativo = :ativo ORDER BY dia_entrega ASC";
            $query = $this->conexao->prepare($sql);
            $query->bindValue(":ativo", $status);
            $query->execute();
            return $query;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function consultarPorId($idLocacao) {
        try {
            $sql = "SELECT * FROM locacao WHERE id = :idLocacao";
            $query = $this->conexao->prepare($sql);
            $query->bindValue(":idLocacao", $idLocacao);
            $query->execute();

            $resultado = $query->fetch(PDO::FETCH_ASSOC);
            $this->id = $resultado["id"];
            $this->descricao = $resultado["descricao"];
            $this->produtos = $resultado["produtos"];
            $this->valor_total = $resultado["valor_total"];
            $this->dia_locacao = $resultado["dia_locacao"];
            $this->dia_entrega = $resultado["dia_entrega"];
            $this->cliente_id = $resultado["cliente_id"];
            $this->usuario_id = $resultado["usuario_id"];
            $this->ativo = $resultado["ativo"];

            return true;
        } catch (PDOException $e) {
            echo "Erro ao consultar categoria " . $e->getMessage();
            return false;
        }
    }

    public function gravar($idLocacao = NULL) {
        try {

            if ($idLocacao == NULL) {
                $sql = "INSERT INTO locacao(descricao, valor_total, produtos, dia_locacao, dia_entrega, usuario_id, cliente_id, ativo )
                    VALUES(:descricao, :valor_total, :produtos, :dia_locacao, :dia_entrega, :usuario_id, :cliente_id, :ativo)";
            } else {
                $sql = "UPDATE locacao SET descricao = :descricao, valor_total = :valor_total, produtos = :produtos, dia_locacao = :dia_locacao, dia_entrega = :dia_entrega, usuario_id = :usuario_id, cliente_id = :cliente_id, ativo = :ativo WHERE id = :idLocacao";
            }

            $query = $this->conexao->prepare($sql);
            $query->bindValue(":descricao", $this->descricao);
            $query->bindValue(":valor_total", $this->valor_total);
            $query->bindValue(":produtos", $this->produtos);
            $query->bindValue(":dia_locacao", $this->dia_locacao);
            $query->bindValue(":dia_entrega", $this->dia_entrega);
            $query->bindValue(":usuario_id", $this->usuario_id);
            $query->bindValue(":cliente_id", $this->cliente_id);
            $query->bindValue(":ativo", $this->ativo);
            if ($idLocacao != NULL) {
                $query->bindValue(":idLocacao", $idLocacao);
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
            $sql = "DELETE FROM locacao WHERE id = :id";
            $query = $this->conexao->prepare($sql);
            $query->bindValue(":id", $id);
            $query->execute();
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function consultarPendencia($idCliente) {
        try {
            $sql = "SELECT id FROM locacao WHERE ativo = 0 AND cliente_id = :idCliente";
            $query = $this->conexao->prepare($sql);
            $query->bindValue(":idCliente", $idCliente);
            $query->execute();
            $resultado = $query->fetch(PDO::FETCH_ASSOC);
            if ($resultado["id"] == NULL) {
                return false;
            } else {
                return true;
            }
        } catch (Exception $ex) {
            $ex->getMessage();
        }
    }

}
