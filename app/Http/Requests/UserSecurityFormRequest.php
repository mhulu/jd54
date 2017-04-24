<?php

namespace App\Http\Requests;

use App\Repository\Eloquent\UserRepo;
use Illuminate\Foundation\Http\FormRequest;
use Star\utils\StarJson;

class UserSecurityFormRequest extends FormRequest
{
    protected $user;
    public function __construct(UserRepo $user)
    {
        $this->user = $user;
    }
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
    public function wantsJson()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if (array_key_exists('password', request()->all())) {
            return [
                'password.oldpass' =>  'required',
                'password.newpass' => 'required|min:6'
            ];
        }
        return [
            'mobile.newmobile' => 'required|max:11',
            'mobile.authcode' => 'required'
        ];
    }
    public function persist()
    {
        if (array_key_exists('password', request()->all())) {
            return $this->updatePassword();
        }
        return $this->updateMobile();
    }
    private function updatePassword()
    {
        $request = $this->request->all()['password'];
        $user = auth()->user();
        if ( !\Hash::check($request['oldpass'], $user->password)) {
            return StarJson::create(403, '您输入的旧密码不正确');
        }

        $this->user->changePassword($user, $request['newpass']);

        return StarJson::create(200, '密码修改成功，请重新登陆');
    }
    private function updateMobile()
    {
        $request = $this->request->all()['mobile'];
        $user = auth()->user();
        if (\Cache::get($request['newmobile'])!=$request['authcode']) {
            return StarJson::create(403, '您输入的验证码不正确');
        } elseif ($request['newmobile'] == $user->mobile) {
            return StarJson::create(403, '您现在的手机号码不就是这个咩？');
        }
        $result = $this->user->update($user->id, ['mobile' => $request['newmobile']]);
        if ($result) {
            return StarJson::create(200, '手机号码已经更改，请重新登录');
        }
    }
}
