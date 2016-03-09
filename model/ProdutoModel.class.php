<?php

class ProdutoModel {

    private $conexao;
    private $codigo;
    private $nome;
    private $cor;
    private $tamanho;
    private $valor_compra;
    private $valor_aluguel;
    private $descricao;
    private $ativo;
    private $imagem;
    private $categoria_id;

    public function getCodigo() {
        return $this->codigo;
    }

    public function setCodigo($codigo) {
        $this->codigo = $codigo;
    }

    public function getCategoria_id() {
        return $this->categoria_id;
    }

    public function setCategoria_id($categoria_id) {
        $this->categoria_id = $categoria_id;
    }

    public function getCor() {
        return $this->cor;
    }

    public function getTamanho() {
        return $this->tamanho;
    }

    public function setCor($cor) {
        $this->cor = $cor;
    }

    public function setTamanho($tamanho) {
        $this->tamanho = $tamanho;
    }

    public function getNome() {
        return $this->nome;
    }

    public function getValor_compra() {
        return $this->valor_compra;
    }

    public function getValor_aluguel() {
        return $this->valor_aluguel;
    }

    public function getDescricao() {
        return $this->descricao;
    }

    public function getAtivo() {
        return $this->ativo;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function setValor_compra($valor_compra) {
        $this->valor_compra = $valor_compra;
    }

    public function setValor_aluguel($valor_aluguel) {
        $this->valor_aluguel = $valor_aluguel;
    }

    public function setDescricao($descricao) {
        $this->descricao = $descricao;
    }

    public function setAtivo($ativo) {
        $this->ativo = $ativo;
    }

    public function getImagem() {
        return $this->imagem;
    }

    public function setImagem($imagem) {
        $this->imagem = $imagem;
    }

    function __construct() {
        try {
            $this->conexao = new PDO("mysql:host=127.0.0.1;dbname=celestial", "root", "");
            $this->conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Erro: " . $e->getMessage();
        }
    }

    public function consultar($ativo = NULL) {
        try {
            if ($ativo == NULL) {
                $sql = "SELECT p.*, c.nome as categoria FROM produto p INNER JOIN categoria c ON c.id = p.categoria_id ORDER BY p.codigo ASC";
            } else {
                $sql = "SELECT p.*, c.nome as categoria FROM produto p INNER JOIN categoria c ON c.id = p.categoria_id WHERE ativo = :ativo ORDER BY p.codigo ASC";
            }
            $query = $this->conexao->prepare($sql);
            if ($ativo != NULL) {
                $query->bindValue(":ativo", $ativo);
            }
            $query->execute();

            return $query;
        } catch (PDOException $e) {
            $e->getMessage();
        }
    }

    public function gravar($idProduto = NULL) {
        try {
            if ($idProduto == NULL) {
                $sql = "INSERT INTO produto(codigo, nome, cor, tamanho, valor_compra, valor_aluguel, descricao, ativo, imagem, categoria_id) 
                    VALUES(:codigo, :nome, :cor, :tamanho, :valor_compra, :valor_aluguel, :descricao, :ativo, :imagem, :categoria_id )";
            } else {
                $sql = "UPDATE produto SET codigo = :codigo, nome = :nome, cor = :cor, tamanho = :tamanho, valor_compra = :valor_compra, valor_aluguel = :valor_aluguel, categoria_id = :categoria_id, descricao = :descricao, imagem = :imagem, ativo = :ativo WHERE id = :idProduto";
            }

            $query = $this->conexao->prepare($sql);

            $query->bindValue(":codigo", $this->codigo);
            $query->bindValue(":nome", $this->nome);
            $query->bindValue(":cor", $this->cor);
            $query->bindValue(":tamanho", $this->tamanho);
            $query->bindValue(":valor_compra", $this->valor_compra);
            $query->bindValue(":valor_aluguel", $this->valor_aluguel);
            $query->bindValue(":descricao", $this->descricao);
            $query->bindValue(":ativo", $this->ativo);
            $query->bindValue(":imagem", $this->imagem);
            $query->bindValue(":categoria_id", $this->categoria_id);

            if ($idProduto != NULL) {
                $query->bindValue(":idProduto", $idProduto);
            }
            $query->execute();

            return true;
        } catch (PDOException $e) {
            echo "erro " . $e;
            return false;
        }
    }

    public function deletar($id) {
        try {
            $sql = "DELETE FROM produto WHERE id = :id";

            $query = $this->conexao->prepare($sql);
            $query->bindValue(":id", $id);
            $query->execute();
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function consultarPorId($idProduto) {
        try {
            $sql = "SELECT * FROM produto WHERE id = :idProduto";
            $query = $this->conexao->prepare($sql);
            $query->bindValue(":idProduto", $idProduto);
            $query->execute();

            $resultado = $query->fetch(PDO::FETCH_ASSOC);
            $this->codigo = $resultado["codigo"];
            $this->nome = $resultado["nome"];
            $this->cor = $resultado["cor"];
            $this->tamanho = $resultado["tamanho"];
            $this->categoria_id = $resultado["categoria_id"];
            $this->valor_compra = $resultado["valor_compra"];
            $this->valor_aluguel = $resultado["valor_aluguel"];
            $this->ativo = $resultado["ativo"];
            $this->descricao = $resultado["descricao"];
            $this->imagem = $resultado["imagem"];
            return true;
        } catch (PDOException $e) {
            echo "Erro ao consultar produto " . $e->getMessage();
            return false;
        }
    }
    
    public function pesquisarTermo($termo){
        try {
            $sql = "SELECT p.*, c.nome AS categoria FROM produto p INNER JOIN categoria c ON p.categoria_id = c.id WHERE p.codigo LIKE '%{$termo}%' OR p.nome LIKE '%{$termo}%' ORDER BY codigo ASC";
            $query = $this->conexao->prepare($sql);
            $query->execute();
            return $query;
        } catch (PDOException $e) {
            echo "Erro ao consultar categoria " . $e->getMessage();
            return false;
        }
    }

}
