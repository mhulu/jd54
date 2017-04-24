<?php

use App\Profile;
use App\Unit;
use App\User;
use Illuminate\Database\Seeder;
use Star\Permission\Models\Permission;
use Star\Permission\Models\Role;

class IcenterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 创建部门
          $office = Unit::create([
              'name' => '办公室'
              ]);

          //创建权限
            $permissionList = config('permissions_menu.permissions');
            foreach ($permissionList  as $permission) {
                Permission::create($permission);
            }

          //创建管理员角色
            $adminRole = Role::create([
                'name' => 'admin',
                'label' => '管理员'
                ]);

            $userRole = Role::create([
                'name' => 'user',
                'label' => '普通管理人员'
                ]);

            $userRole->givePermissionTo($permissionList[0]['name']);

            foreach ($permissionList as $permission) {
                $adminRole->givePermissionTo($permission['name']);
            }
        // 创建默认管理员 
        $admin = User::create([
            'name' => '刘德华',
            'mobile' => '18688889999',
            'password' => bcrypt('password'),
            'email' => 'admin@stario.net'
            ]);
        $partoo = User::create([
            'name' => '郭富城',
            'mobile' => '18669783161',
            'password' => bcrypt('password'),
            'email' => 'partoo@163.com'
        ]);

        
      // 关联用户和部门
        $office->users()->save($admin);

    // 创建一个用户资料
        $profile = Profile::create([
            'nickname' => 'Partoo',
            'avatar' => 'http://tva3.sinaimg.cn/crop.0.0.996.996.180/7b9ce441jw8f6jzisiqduj20ro0roq4k.jpg',
            'sex' => true,
            'birthplace' => 'LA',
            'qq' => '123321'
            ]);
    //关联用户和资料
        $admin->profile()->save($profile);

    }
}
