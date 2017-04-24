<?php

namespace App\Http\Requests;

use App\Repository\Eloquent\UserRepo;
use Illuminate\Foundation\Http\FormRequest;
use Star\SimpleMessage\SmsClient;
use Star\utils\StarJson;

 class FireSmsFormRequest extends FormRequest
{
    protected $user;
    public function __construct(UserRepo $user)
    {
        $this->user = $user;
    }

    public function authorize()
    {
        return true;
    }
    public function rules()
    {
        return [
            'mobile' => 'required|min:11|max:11'
        ];
    }
    // 存储入库，如果发现?findpass则更新密码
    public function fire()
    {
        if (isset($_GET['findpass'])) {
            return $this->forReset();
        } 
        return $this->forNormal();
    }

    private function forReset() {
        $mobile = $this->request->all()['mobile'];
        if ($this->user->pick('mobile', $mobile)) {
            return (new SmsClient)->to($mobile)->type('auth')->send();
        }
        return StarJson::create(403, '该手机号没有注册，请返回注册');
    }

    private function forNormal() {
        $mobile = $this->request->all()['mobile'];
        if ($this->user->pick('mobile', $mobile)) {
            return StarJson::create(403, '该手机号已经被注册');
        }
        return (new SmsClient)->to($mobile)->type('auth')->send();
    }

}