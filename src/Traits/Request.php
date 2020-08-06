<?php

namespace niweisi\yunxin_sdk\Traits;


use niweisi\yunxin_sdk\Exception\niweisiException;
use GuzzleHttp\Client;

trait Request
{
    protected static $timeout = 5;

    public function post($url, $param)
    {
        $time = time();

        $client = new Client([
            'base_uri' => $this->baseUrl,
            'timeout'  => self::$timeout,
            'headers'  => [
                'AppKey'   => $this->AppKey,
                'Nonce'    => $this->Nonce,
                'CurTime'  => $time,
                'CheckSum' => sha1($this->AppSecret . $this->Nonce . $time),
            ]
        ]);

        $response = $client->post($url, ['form_params' => $param]);

        if ($response->getStatusCode() != 200) {
            throw new niweisiException('请求失败: ' . $response->getStatusCode());
        }

        return json_decode($response->getBody(), true);
    }
}
