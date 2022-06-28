<?php
namespace App\Models\Base;

interface Base
{
    public static function obterRegistros();
    public static function obterRegistroPorId($id);
    public static function criarRegistro($registro);
    public static function atualizarRegistro($id, $registro);
    public static function deletarRegistro($id);
}
