<?php
namespace Crmweeek;

// curl 
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

// loger
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

// uuid

    class Weeek 
    {
        private Client $client;
        private Logger $log;

        public function __construct(string $token){
            // Подключение логера
            $this->log = new Logger('weeek');
            $this->log->pushHandler(new StreamHandler(MODX_BASE_PATH . 'logs/weeek.log', Logger::DEBUG));

            // Создание подключения к API WEEEk
            $this->client = new Client([
                'base_uri'  => 'https://api.weeek.net/public/v1/',
                'timeout'   => 5.0,
                'headers'   => [
                    'Authorization' => 'Bearer ' . $token,
                    'Accept'        => 'application/json'
                ]   
            ]);
        }

        public function get(string $method, array $params = [])
        {
            try
            {
                // GET-запрос
                // Подготовка основного запроса
                $path = $method . '/' . ($params['object'] ?? '') . ($params['id'] ? '/' . $params['id'] : '') . ($params['query'] ? '/' . $params['id'] : '');
                // ПОдготовка параметров
                $query = $params['query'] ? ['query'=>$params['query']] : ['query' => []];
                // Отправка зароса
                $response = $this->client->request('GET', $path, $query);
                // Преобразование / получени ответа
                return $response->getBody()->getContents();
            }
            catch(RequestException $e)
            {
                $this->log->error('Ошибка API GET', [
                    'exception' => $e
                ]);
            }
        }

        public function post(string $method, array $params = [])
        {
            try
            {
                //POST
                //Подготовка основного запроса
                $path = $method . '/' . ($params['object'] ?? '');
                //Отправка запроса
                $response = $this->client->request('POST', $path, [
                    'json' => $params['data']
                ]);
                //Получение ответа
                return $response->getBody()->getContents();
            }
            catch(RequestException $e)
            {
                $this->log->error('Ошибка API POST', [
                    'method'    => $method,
                    'data'      => $params,
                    'exception' => $e
                ]);
            }
        }
    }