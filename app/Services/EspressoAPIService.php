<?php

namespace App\Services;

use Exception;
use RuntimeException;

class EspressoAPIService
{
    private function _curl_init()
    {

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_VERBOSE        => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_HTTPHEADER     => [
                'Authorization: Token token=' . env('ESPRESSO_API_TOKEN'),
                'Accept: application/vnd.api+json',
                'Cache-Control: no-cache',
                'Content-Type: application/vnd.api+json',
            ],
        ]);

        return $curl;
    }

    private function _execute($curl)
    {
        try {
            $response = curl_exec($curl);
            if (curl_errno($curl)) {
                throw new RuntimeException(curl_error($curl));
            }
            return json_decode($response, true);
        } catch (Exception $e) {
            dd($e);
        } finally {
            curl_close($curl);
        }
    }

    private function _consultar($url, $parametros)
    {
        $curl   = $this->_curl_init();
        $filtro = !empty($parametros) ? '?' . http_build_query($parametros) : '';
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($curl, CURLOPT_URL, $url . $filtro);
        return $this->_execute($curl);
    }

    public function usuarios($parametros)
    {
        $retornoEspresso = [];
        try {set_time_limit(3600);
            $url        = env('ESPRESSO_API_URL') . '/users';
            $pageNumber = 1;
            while (true) {
                $parametros['page[number]'] = $pageNumber;
                $retorno                    = $this->_consultar($url, $parametros);
                if (empty($retorno['data'])) {
                    break;
                }
                $retornoEspresso = array_merge($retornoEspresso, $retorno['data']);
                $pageNumber++;
            }
        } catch (Exception $e) {
            dd($e);
        }
        return $retornoEspresso;
    }

    public function subcategorias($parametros)
    {
        $retornoEspresso = [];
        try {set_time_limit(3600);
            $url        = env('ESPRESSO_API_URL') . '/subcategories';
            $pageNumber = 1;
            while (true) {
                $parametros['page[number]'] = $pageNumber;
                $retorno                    = $this->_consultar($url, $parametros);
                if (empty($retorno['data'])) {
                    break;
                }
                $retornoEspresso = array_merge($retornoEspresso, $retorno['data']);
                $pageNumber++;
            }
        } catch (Exception $e) {
            dd($e);
        }
        return $retornoEspresso;
    }

    public function tags($parametros)
    {
        $retornoEspresso = [];
        try {set_time_limit(3600);
            $url        = env('ESPRESSO_API_URL') . '/tags';
            $pageNumber = 1;
            while (true) {
                $parametros['page[number]'] = $pageNumber;
                $retorno                    = $this->_consultar($url, $parametros);
                if (empty($retorno['data'])) {
                    break;
                }
                $retornoEspresso = array_merge($retornoEspresso, $retorno['data']);
                $pageNumber++;
            }
        } catch (Exception $e) {
            dd($e);
        }
        return $retornoEspresso;
    }

    public function despesas($parametros)
    {
        $retornoEspresso = [];
        try {
            set_time_limit(3600);
            $url        = env('ESPRESSO_API_URL') . '/expenses';
            $pageNumber = 1;
            while (true) {
                $parametros['page[number]'] = $pageNumber;
                $retorno                    = $this->_consultar($url, $parametros);
                if (empty($retorno['data'])) {
                    break;
                }
                $retornoEspresso = array_merge($retornoEspresso, $retorno['data']);
                $pageNumber++;
            }
        } catch (Exception $e) {
            dd($e);
        }
        return $retornoEspresso;
    }

    public function adiantamentos($parametros)
    {
        $retornoEspresso = [];
        try {
            set_time_limit(3600);
            $url        = env('ESPRESSO_API_URL') . '/up_fronts';
            $pageNumber = 1;
            while (true) {
                $parametros['page[number]'] = $pageNumber;
                $retorno                    = $this->_consultar($url, $parametros);
                if (empty($retorno['data'])) {
                    break;
                }
                $retornoEspresso = array_merge($retornoEspresso, $retorno['data']);
                $pageNumber++;
            }
        } catch (Exception $e) {
            dd($e);
        }
        return $retornoEspresso;
    }

}
