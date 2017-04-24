<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\UserSecurityFormRequest;
use App\Repository\Eloquent\MenuRepo;
use App\Repository\Eloquent\NotificationsRepo;
use App\Repository\Eloquent\SiteRepo;
use App\Repository\Eloquent\UserRepo;
use App\Transformers\MeInfoTransformer;
use Illuminate\Http\Request;
use Star\utils\StarJson;

class HomeController extends ApiController
{

	protected $userRepo;

	public function __construct(UserRepo $userRepo)
	{
		parent::__construct();

		$this->middleware( function ($request, $next) {
			$this->user = auth()->user();
			return $next($request);
		});

		$this->userRepo = $userRepo;
	}
	public function index()
	{
		return (new siteRepo)->getStatistics();
	}
	public function info()
	{
		return $this->respondWithItem($this->user, new MeInfoTransformer);
	}

	/**
	 * 更新本人资料,此处没有新建FormRequest，直接调用的UserRepo
	 * @return json 返回MeInfoTransformer供前台vuex刷新
	 */
	public function update()
	{
		$profile = request()->input('profile');
		$base = request()->input('baseInfo');
		$saveProfile = $this->userRepo->saveProfile($this->user->id, $profile);
		$saveBase = $this->userRepo->update($this->user->id, $base);
		return $this->respondWithItem($saveBase, new MeInfoTransformer);
	}

	/**
	 * 更改本人密码，调用的FormRequest
	 */
	public function changePassword(UserSecurityFormRequest $request)
	{
		return $request->persist();
	}

	/**
	 * 更改本人手机，调用的FormRequest
	 */
	public function changeMobile(UserSecurityFormRequest $request)
	{
		return $request->persist();
	}

	public function avatar()
	{
		return $this->userRepo->avatar($this->user, request('file'));
	}

	/**
	 * 当前登录用户的菜单，依照权限不同显示
	 */
	public function menu()
	{
		$result = $this->makeMenu($this->user);
		return StarJson::create(200, $result);
	}

	/**
	 * 生成事件时间轴
	 *  notifications中的notifiable_type 形如"App\User" 会转换成 "user"
	 */
	public function timeline()
	{   
		return (new NotificationsRepo($this->user))->timeline();
	}
	public function deleteNotifiaction($id)
	{
		return (new NotificationsRepo($this->user))->delete($id);
	}

	protected function makeMenu($user)
	{
		$menu = collect(config('permissions_menu.menu'));
		$permissions = $user->getAllPermissions();
		$list = collect();
		foreach ($permissions as $permission) {
			foreach ($menu as $item) {
				if ($permission->name == $item['permission']) {
					$list[] = $item;
				}
			}
		}
		return $list;
	}
    
  }
