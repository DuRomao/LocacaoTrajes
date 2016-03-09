<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Cliente
 *
 * @author Hellen
 */
class Cliente {

    private function formulario() {
        return file_get_contents("view/html/cliente-form.html");
    }

    public function listar() {
        $grid = file_get_contents("view/html/clientes.html");
        $linhas = "";
        $locacaoModel = new LocacaoModel();
        $clienteModel = new ClienteModel();
        $registros = $clienteModel->consultar();
        while ($registro = $registros->fetch(PDO::FETCH_ASSOC)) {
            $pendencia = ($locacaoModel->consultarPendencia($registro["id"]) == true) ? "sim" : "não";
            $linhas .= "<tr>";
            $linhas .= "<td>" . $registro["nome"] . "</td>";
            $linhas .= "<td>" . $pendencia . "</td>";
            $linhas .= "<td>" . $registro["telefone"] . "</td>";
            $linhas .= "<td>" . $registro["celular"] . "</td>";
            $linhas .= "<td><a href='index.php?modulo=Cliente&acao=info&chave={$registro['id']}'><button type='button' class='btn btn-default'><i class='glyphicon glyphicon-zoom-in'></i></button></a><a href='index.php?modulo=cliente&acao=editar&chave={$registro['id']}'><button type='button' class='btn btn-default'><i class='glyphicon glyphicon-wrench'></i></button></a> <a href='index.php?modulo=cliente&acao=excluir&chave={$registro['id']}'><button type='button' class='btn btn-default'><i class='glyphicon glyphicon-remove'></i></button></a></td>";
            $linhas .= "</tr>";
        }
        $html = str_replace("#LINHAS#", $linhas, $grid);

        return $html;
    }

    public function cadastrar() {
        $formulario = $this->formulario();
        $html = str_replace("#TITULO#", "Cadastro de Cliente", $formulario);
        $html = str_replace("#NOME#", "", $html);
        $html = str_replace("#EMAIL#", "", $html);
        $html = str_replace("#SENHA#", "", $html);
        $html = str_replace("#RG#", "", $html);
        $html = str_replace("#CPF#", "", $html);
        $html = str_replace("#RUA#", "", $html);
        $html = str_replace("#NUMERO#", "", $html);
        $html = str_replace("#CEP#", "", $html);
        $html = str_replace("#COMPLEMENTO#", "", $html);
        $html = str_replace("#BAIRRO#", "", $html);
        $html = str_replace("#TELEFONE#", "", $html);
        $html = str_replace("#CELULAR#", "", $html);
        $html = str_replace("#ID#", "", $html);

        return $html;
    }

    public function salvar() {
        if (isset($_POST["nome"])) {
            $clienteModel = new ClienteModel();
            $clienteModel->setNome($_POST["nome"]);
            $clienteModel->setEmail($_POST["email"]);
            $clienteModel->setRg($_POST["rg"]);
            $clienteModel->setCpf($_POST["cpf"]);
            $clienteModel->setRua($_POST["rua"]);
            $clienteModel->setNumero($_POST["numero"]);
            $clienteModel->setCep($_POST["cep"]);
            $clienteModel->setBairro($_POST["bairro"]);
            $clienteModel->setTelefone($_POST["telefone"]);
            $clienteModel->setCelular($_POST["celular"]);
            $clienteModel->gravar(Aplicacao::$chave);

            header('Location: index.php?modulo=Cliente&acao=listar');

            return true;
        }
    }

    public function excluir() {
        if (Aplicacao::getValorSessao("tipoUsuario") == "ADMINISTRADOR") {
            if (Aplicacao::$chave != "") {
                $clienteModel = new ClienteModel();
                if ($clienteModel->deletar(Aplicacao::$chave)) {
                    $html = '<p class="bg-success">Registro excluido com sucesso!</p>' . $this->listar();
                } else {
                    $html = '<p class="bg-info">Não foi possível deletar a locação!</p>' . $this->listar();
                }
            }
        } else {
            $html = '<p class="bg-danger">Você não tem permissão de excluir registros!</p>' . $this->listar();
        }
        return $html;
    }

    public function info() {
        $info = file_get_contents("view/html/cliente-info.html");
        $html = str_replace("#TITULO#", "Informações do Cliente", $info);
        $locacaoModel = new LocacaoModel();
        $clienteModel = new ClienteModel();
        $clienteModel->consultarPorId(Aplicacao::$chave);
        $b = ($locacaoModel->consultarPendencia(Aplicacao::$chave) == true) ? "sim" : "não";
        $html = str_replace("#NOME#", $clienteModel->getNome(), $html);
        $html = str_replace("#EMAIL#", $clienteModel->getEmail(), $html);
        $html = str_replace("#CPF#", $clienteModel->getCpf(), $html);
        $html = str_replace("#RG#", $clienteModel->getRg(), $html);
        $html = str_replace("#ENDERECO#", $clienteModel->getRua() . ", " . $clienteModel->getNumero() . " - " . $clienteModel->getBairro(), $html);
        $html = str_replace("#CEP#", $clienteModel->getCep(), $html);
        $html = str_replace("#FIXO#", $clienteModel->getTelefone(), $html);
        $html = str_replace("#CELULAR#", $clienteModel->getCelular(), $html);
        $html = str_replace("#PENDENCIA#", $b, $html);
        $html = str_replace("#ID#", Aplicacao::$chave, $html);

        return $html;
    }

    public function editar() {
        $formulario = $this->formulario();
        $clienteModel = new ClienteModel();
        $clienteModel->consultarPorId(Aplicacao::$chave);
        $html = str_replace("#TITULO#", "Editar Cliente", $formulario);
        $html = str_replace("#NOME#", $clienteModel->getNome(), $html);
        $html = str_replace("#EMAIL#", $clienteModel->getEmail(), $html);
        $html = str_replace("#RG#", $clienteModel->getRg(), $html);
        $html = str_replace("#CPF#", $clienteModel->getCpf(), $html);
        $html = str_replace("#RUA#", $clienteModel->getRua(), $html);
        $html = str_replace("#NUMERO#", $clienteModel->getNumero(), $html);
        $html = str_replace("#CEP#", $clienteModel->getCep(), $html);
        $html = str_replace("#BAIRRO#", $clienteModel->getBairro(), $html);
        $html = str_replace("#TELEFONE#", $clienteModel->getTelefone(), $html);
        $html = str_replace("#CELULAR#", $clienteModel->getCelular(), $html);
        $html = str_replace("#ID#", Aplicacao::$chave, $html);

        return $html;
    }
    public function pesquisa() {
        if (isset($_POST["pesquisar"])) {
            $termo = $_POST["pesquisar"];
            $linhas = "";
            $clienteModel = new ClienteModel();
            $locacaoModel = new LocacaoModel();
            $registros = $clienteModel->pesquisarTermo($termo);
            while ($registro = $registros->fetch(PDO::FETCH_ASSOC)) {
            $pendencia = ($locacaoModel->consultarPendencia($registro["id"]) == true) ? "sim" : "não";
            $linhas .= "<tr>";
            $linhas .= "<td>" . $registro["nome"] . "</td>";
            $linhas .= "<td>" . $pendencia . "</td>";
            $linhas .= "<td>" . $registro["telefone"] . "</td>";
            $linhas .= "<td>" . $registro["celular"] . "</td>";
            $linhas .= "<td><a href='index.php?modulo=Cliente&acao=info&chave={$registro['id']}'><button type='button' class='btn btn-default'><i class='glyphicon glyphicon-zoom-in'></i></button></a><a href='index.php?modulo=cliente&acao=editar&chave={$registro['id']}'><button type='button' class='btn btn-default'><i class='glyphicon glyphicon-wrench'></i></button></a> <a href='index.php?modulo=cliente&acao=excluir&chave={$registro['id']}'><button type='button' class='btn btn-default'><i class='glyphicon glyphicon-remove'></i></button></a></td>";
            $linhas .= "</tr>";
            
            }
            $grid = file_get_contents("view/html/clientes.html");
            $html = str_replace("#LINHAS#", $linhas, $grid);
            return $html;
        }
    }
}
