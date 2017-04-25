<?php

namespace App\Http\Controllers;

class WechatController extends Controller {

	public function serve() {
		$wechat = app('wechat');
		return $wechat->server->serve();
	}

	public function getUserInfo() {
		$user = session('wechat.oauth_user'); // 拿到授权用户资料
		return $user;
	}
}