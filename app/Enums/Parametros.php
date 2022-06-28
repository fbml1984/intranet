<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class Parametros extends Enum
{
    const SYSTEM_ROUTINE                = 'rotina.sistema';
    const CHAVE_USUARIO_LOGADO          = 'administrador';
    const INTEGRA_TIPO_REGISTRO_DESPESA = 'RD';
    const INTEGRA_TIPO_ADIANTAMENTO     = 'AD';
    const PODE_SER_EXCLUIDO             = 1;
    const NAO_PODE_SER_EXCLUIDO         = 0;
    const ID_USUARIO_SISTEMA            = 1;
}
