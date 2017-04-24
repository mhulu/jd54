<?php

namespace App\Http\Requests;

use App\Repository\Eloquent\UserRepo;
use Illuminate\Foundation\Http\FormRequest;
use Star\utils\StarJson;

class UserManageFormRequest extends FormRequest
{
    protected $userRepo;
    public function __construct(UserRepo $userRepo)
    {
        $this->userRepo = $userRepo;
    }
    public function authorize()
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
        if ($this->isMethod('post')) {
            return [
                'baseInfo.mobile' => ['required', 'regex:/^1[3|4|5|7|8]\d{9}$/', 'unique:users,mobile'],
                'baseInfo.email' => ['required', 'email'],
                'baseInfo.name' => ['required', 'regex:/[\x{4e00}-\x{9fa5}]+.*/u'],
                'baseInfo.status' => 'required',
                'profile.birthday' => 'date_format:Y-m-d'
            ];
        } elseif ($this->isMethod('put')) {
            return [
                'baseInfo.mobile' => ['regex:/^1[3|4|5|7|8]\d{9}$/', 'unique:users,mobile'],
                'baseInfo.email' => ['required', 'email'],
                'baseInfo.name' => ['required', 'regex:/[\x{4e00}-\x{9fa5}]+.*/u'],
                'baseInfo.status' => 'required',
                'profile.birthday' => 'date_format:Y-m-d'
            ];
        }
    }
    public function messages()
    {
        return [
             'baseInfo.mobile.unique' => '该手机号码已经注册',
             'baseInfo.mobile.required' => '手机号码必须填写',
             'baseInfo.email.required' => '电子邮箱必须填写',
             'baseInfo.name.required' => '姓名必须填写',
             'baseInfo.name.regex' => '姓名必须为汉字'
        ];
    }
    
   /**
    * 自动判断update还是create
    *
    * @param  $id
    * @return void
    */
    public function persist($id = null)
    {
        $profile = $this->input('profile');
        $base = $this->input('baseInfo');
        $unit_id = $this->input('unit');
        $info = array_merge($base, ['unit_id' => $unit_id]);
        // 如果有password则加密
        if (array_has($info, 'password')) {
            $password = array_pull($info, 'password');
            $info = array_merge($info, ['password' => bcrypt($password)]);
        }
        $permissions = $this->input('permissions');

        // 如果是更新
        if ($id != null) {
            $user = $this->userRepo->find($id);
            $saveProfile = $this->userRepo->saveProfile($id, $profile);
            $saveBase = $this->userRepo->update($id, $info);
            if ($saveProfile && $saveBase) {
                return StarJson::create(200, '用户资料修改成功');
            }
            // 如果是创建
        } elseif ($id == null) {
            $saveBase = $this->userRepo->create($info);
            $saveProfile = $this->userRepo->saveProfile($saveBase->id, $profile);
            if ($saveProfile && $saveBase) {
                return StarJson::create(200, '用户创建成功');
            }
        }
    }
}
