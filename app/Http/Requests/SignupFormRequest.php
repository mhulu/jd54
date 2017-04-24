<?php

namespace App\Http\Requests;

use App\Events\ChangeCredentials;
use App\Repository\Eloquent\UserRepo;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Http\FormRequest;
use Star\utils\StarJson;

class SignupFormRequest extends FormRequest
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

    public function wantsJson()
    {
        return true;
    }

    // public function response(array $errors)
    // {
    //     return StarJson::create(403, array_flatten($errors)[0]);
    // }

    public function rules()
    {
        if (isset($_GET['findpass'])) {
            return [
                'mobile' => 'required|max:11',
                'password' => 'required|min:6',
                'authCode' => 'required'
            ];
        }

        return [
            'mobile' => 'required|max:11',
            'password' => 'required|min:6',
            'name' => 'required',
            'authCode' => 'required'
        ];
    }

    // 存储入库，如果发现?findpass则更新密码
    public function persist()
    {
        $request = $this->request->all();
        $authCode = $request['authCode'];
        $mobile = $request['mobile'];
        if (\Cache::get($mobile)!=$authCode) {
            return StarJson::create(403, '您输入的验证码不正确');
        }
        if (isset($_GET['findpass'])) {
            return $this->updatePassword();
        } else {
            return $this->register();
        }
    }

    /**
     * 注册用户，并自动分配为user角色
     */
    private function register()
    {
        $request = $this->request->all();
        try {
            if ($this->user->pick('mobile', $request['mobile'])) {
                return StarJson::create(403, '该手机号已经注册，请直接登陆');
            } 
        } catch (Exception $e) {
                return StarJson::create(['result' => [$e]], 500);
        }

        return $this->user->create([
                'mobile' => $request['mobile'],
                'name' => $request['name'],
                'password' => bcrypt($request['password'])
            ]);
    }

    private function updatePassword()
    {
        $request = $this->request->all();
        $user = $this->user->pick('mobile', $request['mobile']);
        try {
            if (! $user) {
                return response()->json(['result' => ['该手机号没有注册，请返回注册']], 403);
            }
        } catch (Exception $e) {
                return response()->json(['result' => [$e]], 500);
        }
        $this->user->changePassword($user, $request['password']);
        event(new ChangeCredentials($user));
        return StarJson::create(200, '密码修改成功，请重新登陆');
    }
}
