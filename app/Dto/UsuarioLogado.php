<?php

namespace App\Dto;

class UsuarioLogado
{

    public $usuario_id     = null;
    public $cpf            = null;
    public $rota           = null;
    public $codFornecedor  = null;
    public $nome           = null;
    public $apelido        = null;
    public $dataNascimento = null;
    public $sexo           = null;
    public $email          = null;

    public function __construct($usuario_id, $cpf, $rota, $codFornecedor, $nome, $apelido, $dataNascimento, $sexo, $email)
    {
        $this->usuario_id     = $usuario_id;
        $this->cpf            = $cpf;
        $this->rota           = $rota;
        $this->codFornecedor  = $codFornecedor;
        $this->nome           = $nome;
        $this->apelido        = $apelido;
        $this->dataNascimento = $dataNascimento;
        $this->sexo           = $sexo;
        $this->email          = $email;
    }

}
