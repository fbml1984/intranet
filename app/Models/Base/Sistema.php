<?php

namespace App\Models\Base;

use Illuminate\Support\Facades\DB;

class Sistema
{

    public static function obterPorNamespace($namespace)
    {
        return collect(DB::select("
            select
                *
            from
                sistema
            where
                namespace = ?
        ", [$namespace]))->first();
    }

    public static function create($registro): int
    {
        return DB::table('sistema')->insertGetId($registro);
    }

}
