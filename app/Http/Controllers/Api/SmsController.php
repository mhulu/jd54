<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FireSmsFormRequest;

class SmsController extends Controller
{
  public function authCode(FireSmsFormRequest $request)
  {
    return $request->fire();
  }

}