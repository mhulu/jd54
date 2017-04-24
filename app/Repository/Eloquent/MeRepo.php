<?php

namespace Star\Repository\Eloquent;

// use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Star\Transformers\NotificationTransformer;
use Star\Transformers\UserTransformer;

/**
* 
*/
class MeRepo 
{
	public function __construct()
	{
		$this->user = Auth::user();
	}
	public function userInfo()
	{
		return fractal($this->user, new UserTransformer())->toJson();
	}

	public function menu()
	{
		$menu = collect(array_collapse(config('menu')));
		$permissions = \Auth::user()->getAllPermissions()->map(function($permission) {
			return $permission['name'];
		});
		$theMenu = collect();
		foreach ($permissions as $key => $permission) {
			foreach ($menu as $key => $item) {
				if ($item['permission'] == $permission) {
					$theMenu[] = $item;
				}
			}
		}
		return fractal($theMenu, new UserTransformer())->toJson();
	}

	public function notifications()
	{
		// $notifications = $this->user->notifications;
		// $data = $notifications->groupBy(function($date){
		// 	return Carbon::parse($date->created_at)->format('Y-m-d');
		// });
		// return $data;
		return $this->user->notifications()->paginate(5);
	}
}