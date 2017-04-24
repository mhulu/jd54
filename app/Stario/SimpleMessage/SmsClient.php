<?php
/**
 * *************************************************************************
 *  * Copyright (C) STARIO.NET - All Rights Reserved
 *  * @file        SmsClient.php
 *  * @author      Partoo
 *  * @site       http://www.stario.net
 *  * @date        2017-02-21 22:24:52
 *
 */

namespace Star\SimpleMessage;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Psr\Http\Message\ResponseInterface;
use Star\utils\StarJson;

/**
 * Class SmsClient
 * @package Star\SimpleMessage
 * 发送短信客户端类
 * 使用方法：
 * return (new SmsClient)->to($recipient)->type('code')->send()
 * 或者 return (new SmsClient('18666889999', 'TYPE'))->send()
 * config中的sms.php定义了代理商的常用参数，目前为BechProxy
 */
class SmsClient
{
    protected $proxy;
    protected $url;
    public $type; // 短信类型，目前有code, success, warning, info, danger五种，限于短信管制，没有加入自定义内容项
    public $recipient; // 手机号码
    protected $httpClient;
    public $response;

    public function __construct($type = '', $to = '')
    {
        $this->proxy = config('sms.Default');
        $this->url = config('sms.' .$this->proxy. '.Url');
        $this->httpClient = new Client([
                'timeout' => 5,
                'connect_timeout' => 5
            ]);
        $this->recipient = $to;
        $this->type = $type;
    }

    public function to($recipient)
    {
        $this->recipient = $recipient;
        return $this;
    }

    public function type($messageType)
    {
        $this->type = $messageType;
        return $this;
    }

    public function send()
    {
        $base = [
            'accesskey' => config('sms.' .$this->proxy. '.Params.akey'),
            'secretkey' => config('sms.' .$this->proxy. '.Params.skey'),
        ];

        $params = array_merge($base, $this->makeParams($this->makeMessage($this->type), $this->recipient));
        $request = $this->httpClient->post(config('sms.'.$this->proxy.'.Url'), ['form_params' => $params]);
        $response = $this->getResponse($request);
        return $this->response = $this->formatResponse($response);
    }


    private function getResponse(ResponseInterface $response)
    {
        $body = $response->getBody();
        $content = $body->getContents();
        $statusCode = $response->getStatusCode();
        $result = [
        'statusCode' => $statusCode,
        'content' => $content
        ];
        return $result;
    }


    private function makeParams($message, $recipient)
    {
        $params = [
          'mobile' => $recipient,
          'content' => $message
        ];

        return $params;
    }

    /**
     * 生成验证码后根据配置模板生成内容，并添加缓存（存储15分钟）
     * @param  String $key   [缓存key，如手机号码]
     * @param  integer $len [验证码位数]
     * @return mixed
     */
    private function generateCode($len = 4)
    {
        $code = self::randomNum($len);
        Cache::put($this->recipient, $code, 15);
        $pattern = '/{\w+}/';
        $content = preg_replace($pattern, $code, config('sms.Templates.auth'));
        return $content;
    }

    private function randomNum($len = 4)
    {
        return str_pad(rand(0, pow(10, $len) -1), $len, '0', STR_PAD_LEFT);
    }

    private function makeMessage($messageType)
    {
        switch ($messageType) {
            case 'success':
                return config('sms.Templates.success');
            break;

            case 'danger':
                return config('sms.Templates.danger');
            break;

            case 'error':
                return config('sms.Templates.error');
            break;

            case 'info':
                return config('sms.Templates.info');
            break;

            case 'warning':
                return config('sms.Templates.warning');
            break;

            default:
                return $this->generateCode(config('sms.AuthcodeLength'));
        }
    }

    /**
         * 抽象方法实现，格式化输出
         */
    private function formatResponse($response)
    {
        $statusCode = $response['statusCode'];
        $content = $response['content'];
        $errCode = config('sms.' .$this->proxy. '.ErrorCode');
        $result = json_decode($content)->result;
        if ($statusCode == '200') {
            if ($result == '01') {
                $desc = '短信已发送，请注意查收';
                return StarJson::create(200, $desc);
            }
            $desc = $errCode[$result];
            return StarJson::create(403, $desc);
        } else {
            return StarJson::create(500);
        }
    }
}
