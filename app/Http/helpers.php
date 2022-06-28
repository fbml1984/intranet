<?php
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
    // function executeQuery($query)
    // {
    //     try {

    //         $curl = curl_init();

    //         curl_setopt_array($curl, array(
    //             CURLOPT_URL            => env('API_URL') . '/api/conn?op=2',
    //             CURLOPT_RETURNTRANSFER => true,
    //             CURLOPT_ENCODING       => '',
    //             CURLOPT_MAXREDIRS      => 10,
    //             CURLOPT_TIMEOUT        => 0,
    //             CURLOPT_FOLLOWLOCATION => true,
    //             CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
    //             CURLOPT_CUSTOMREQUEST  => 'POST',
    //             CURLOPT_POSTFIELDS     => '000000' . base64_encode($query) . '00',
    //             CURLOPT_HTTPHEADER     => array(
    //                 'Content-Type: application/json',
    //             ),
    //         ));

    //         $response = curl_exec($curl);

    //         curl_close($curl);
    //         return json_decode($response);

    //     } catch (Exception $e) {
    //         return [];
    //     }
    // }
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
