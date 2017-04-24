<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserManageFormRequest;
use App\Repository\Eloquent\UserRepo;
use App\Transformers\UserInfoTransformer;
use App\Transformers\UserTransformer;
use Star\utils\StarJson;
use Excel;

class UserController extends ApiController
{
    protected $userRepo;
    
    public function __construct(UserRepo $userRepo)
    {
        parent::__construct();
        $this->userRepo = $userRepo;
        $this->middleware('permission:manage_users');
    }
    
    public function index()
    {
        $users = $this->userRepo->dataTableProvider(['unit']);
        return $this->respondWithPaginator($users, new UserTransformer());
    }
    
    public function update(UserManageFormRequest $request, $id)
    {
        $this->userRepo->find($id)->syncPermissions($request->permissions);
        return $request->persist($id);
    }
    public function edit($id)
    {
        $user = $this->userRepo->find($id);
        return $this->respondWithItem($user, new UserInfoTransformer());
    }
    public function store(UserManageFormRequest $request)
    {
        return $request->persist();
    }
    public function destroy($id)
    {
        $deleted = $this->userRepo->delete($id);
        if ($deleted) {
            return StarJson::create(200, '成功删除用户');
        }
    }
    
    // export excel
    public function export()
    {
        $users = $this->userRepo->with(['unit'])->all();
        $data = $users->map(function ($u) {
            return [
            '姓名' => $u->name,
            '手机号码' => $u->mobile,
            '部门' => $u->unit->name,
            '创建日期' => $u->created_at,
            '登录日期' => $u->last_login
            ];
        })->toArray();
        
        $export = Excel::create('内部人员列表', function($excel) use ($data)
        {
            
            $excel->sheet('内部人员列表', function($sheet) use ($data)
            {
                $sheet->fromArray($data)
                ->prependRow(1, [
                '内部人员管理表'
                ])
                ->mergeCells('A1:E1')
                ->cell('A1', function($cell) {
                    $cell->setFont([
                    'size' => '16',
                    'bold' => true
                    ])
                    ->setAlignment('center');
                })
                ->setWidth([
                'A' => 10,
                'B' => 20,
                'C' => 10,
                'D' => 30,
                'E' => 30
                ]);
            });
        })->store('xls', false ,true);
        
        return StarJson::create(200, [
        'url' => url('storage/exports/'.$export['file'])
        ]);
    }
    
}