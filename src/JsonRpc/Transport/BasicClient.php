<?php
namespace JsonRpc\Transport;


class BasicClient
{

    public $output = '';
    public $error = '';


    public function send($method, $url, $json, $headers = array(), $sync = false)
    {

        $header = 'Content-Type: application/json';

        if (!in_array($header, $headers)) {
            $headers[] = $header;
        }

//        $opts = array(
//            'http' => array(
//                'method' => $method,
//                'header' => implode("\r\n", $headers),
//                'content' => $json,
//            )
//        );
//
//        $context = stream_context_create($opts);
//        #$response = @file_get_contents($url, false, $context);
//        $response = file_get_contents($url, false, $context);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if ($sync) {
            curl_setopt($ch, CURLOPT_TIMEOUT, 1);
        }
        if ($method == 'POST') {
            curl_setopt($ch, CURLOPT_URL, $url);
            // post数据
            curl_setopt($ch, CURLOPT_POST, 1);
            // post的变量
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        } else {
            curl_setopt($ch, CURLOPT_URL, $url.'?content='.$json);
        }
        $response = curl_exec($ch);
        curl_close($ch);

        if ($response === false) {
            $this->error = 'Unable to connect to ' . $url;
            return;
        }

        $this->output = $response;

        return true;

    }

}

