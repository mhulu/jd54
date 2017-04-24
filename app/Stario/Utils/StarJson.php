<?php
namespace Star\utils;

/**
* 生成Json静态类
*/
class StarJson
{

  public static function create($statusCode, $data = '操作成功')
  {
    switch ($statusCode) {
      case 200:
        return response()->json(['data' => $data], 200);
        break;
      case 304:
        return response()->json(['data' => '更新失败'], 304);
        break;
      case 400:
        return response()->json(['data' => '请求错误'], 400);
        break;
      case 401:
        return response()->json(['data' => '认证失败'], 401);
        break;
      case 403:
        return response()->json(['data' => $data], 403);
        break;
      case 404:
        return response()->json(['data' => '资源未找到'], 404);
        break;
      case 406:
        return response()->json(['data' => '请求的格式有误'], 406);
        break;
      case 500:
        return response()->json(['data' => '服务器错误'], 500);
        break;
      default:
        return response()->json(['data' => $data], 404);
        break;
    }
  }
}