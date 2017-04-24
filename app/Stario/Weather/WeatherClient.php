<?php
namespace Star\Weather;

use GuzzleHttp\Client;
use Cache;

class WeatherClient
{
    public static function get()
    {
        if (Cache::has('weather')) {
            return Cache::get('weather');
        } else {
            $result = static::fetchWeather();
            Cache::put('weather', $result, 240);
            return $result;
        }
    }
    
    private static function fetchWeather()
    {
        $url = 'http://jisutqybmf.market.alicloudapi.com/weather/query';
        $appkey = 'APPCODE f95f761e60e7478e83803875cf3e1164';
        $client = new Client();
        
        return $client->request('GET', $url, [
        'headers' => [
        'Authorization' => $appkey
        ],
        'query' => [
        'city' => '胶州市'
        ]
        ])->getBody()->getContents();
    }
}