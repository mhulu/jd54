<?php

namespace App\Http\Controllers;

class WechatController extends Controller {
	public function serve() {
		$wechat = app('wechat');
		return $wechat->server->serve();
	}
}
