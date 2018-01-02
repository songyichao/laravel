<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;

class TestController extends Controller
{


    function start()
    {
        ob_start(); //打开输出控制缓冲
        echo '<html xmlns:o="urn:schemas-microsoft-com:office:office"';
        echo 'xmlns:w="urn:schemas-microsoft-com:office:word"';
        echo 'xmlns="http://www.w3.org/TR/REC-html40">';
    }

    function save($path)
    {
        echo "</html>";
        $data = ob_get_contents();    //返回输出缓冲区的内容
        ob_end_clean();             //清空缓冲区并关闭输出缓冲
        $this->writeFile($path, $data);   //将缓冲区内容写入word
    }

    function writeFile($fn, $data)
    {
        $fp = fopen($fn, "wb+");
        fwrite($fp, $data);
        fclose($fp);
    }

    public function export()
    {
        $a = 10;

        $b = $a === 10 ? : 0;
        dump($b);
    }

    public function testHttp()
    {
        $file = 'data.json';
        $file_path = app_path('Http/Controllers/' . $file);
        $data = file_get_contents($file_path);
        $client = new Client(['timeout' => 500]);
        $option['form_params'] = [
            'type' => 'b107',
            'token' => 'd53cc2696364632b6ce92aaaaff8f8a8',
            'query' => json_decode($data, true),
        ];
        $option['expect'] = 104857600;
        ini_set("post_max_size", "64M");
        $resp = $client->request('post', '127.0.0.1:3025/common/mq', $option);
        $resp_content = $resp->getBody()->getContents();
        var_dump($resp_content);
        dd($resp);
    }

    public function testHttp2()
    {
        $file = 'data2.json';
        $file_path = app_path('Http/Controllers/' . $file);
        $data = file_get_contents($file_path);
        $client = new Client(['timeout' => 500]);
        $option['form_params'] = [
            'type' => 'b107',
            'token' => 'd53cc2696364632b6ce92aaaaff8f8a8',
            'query' => $data,
        ];
        $option['expect'] = 104857600;
        $resp = $client->request('post', '10.10.75.208:3025/common/mq', $option);
        $resp_content = $resp->getBody()->getContents();
        var_dump($resp_content);
        dd($resp);
    }
}