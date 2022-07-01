<?php

if (!function_exists('slugify')) {
    function slugify($text, string $divider = '-')
    {
        // replace non letter or digits by divider
        $text = preg_replace('~[^\pL\d]+~u', $divider, $text);
        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);
        // trim
        $text = trim($text, $divider);
        // remove duplicate divider
        $text = preg_replace('~-+~', $divider, $text);
        // lowercase
        $text = strtolower($text);
        if (empty($text)) {
            return 'n-a';
        }
        return $text;
    }
}

if (!function_exists('executeQuery')) {
    function executeQuery($query)
    {
        try {
            $string = 'c15125' . base64_encode($query) . 'd25dc=';
            $opts   = array(
                'http' => array(
                    'method'  => 'POST',
                    'header'  => "Content-Type: text/json\r\n" . "Authorization: Basic NAOUSAISSO\r\n",
                    'content' => $string,
                    'timeout' => 60,
                ),
            );
            $context = stream_context_create($opts);
            $retorno = file_get_contents("http://200.170.152.141:8080/index.php/api/conn?op=2", false, $context);
            $retorno = json_decode($retorno);
            return collect($retorno);
        } catch (Exception $e) {
            return [];
        }
    }
}

if (!function_exists('abrirArquivo')) {
    function abrirArquivo($nomeArquivo, $extensao, $arquivo, $base64 = true)
    {
        switch ($extensao) {
            case "doc":
            case "docx":
                header('Content-Type: application/octet-stream');
                break;
            case "xls":
            case "xlsx":
                header('Content-Type: application/vnd.ms-excel');
                break;
            case "ocx":
                header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
                break;
            case "lsx":
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                break;
            default:
                header("Content-type: application/pdf");
                break;
        }
        header("Content-Disposition: inline; filename=\"" . slugify($nomeArquivo) . "\"");
        if ($base64) {
            echo file_get_contents("data://application/{$extensao};base64,{$arquivo}");
        } else {
            echo $arquivo;
        }
        die;
    }
}

if (!function_exists('generateRandomString')) {
    function generateRandomString($length = 10)
    {
        $characters       = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ@#$!';
        $charactersLength = strlen($characters);
        $randomString     = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[mt_rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}

if (!function_exists('getIp')) {
    function getIp()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
}
