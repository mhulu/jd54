<?php
namespace App\Repository\Eloquent;

use App\Pop;
use App\Unit;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

/**
* 提供站点的一些统计数据
* 使用get可以获得数据
* 使用handle可以从数据中获取，通常用于Schedule调用
*/
class SiteRepo
{
	public function getStatistics()
	{
		// $value = Cache::remember('statistics', 2, function () {
			return $this->handle();
		// });
		return response()->json($value, 200);
	}
	private function handle()
	{
		$totalUser = User::count();
		$totalUnit = Unit::count();
		$totalPop = Pop::count();
		$total = [
			'users' => $totalUser,
			'units' => $totalUnit,
			'pops' => $totalPop,
			'aged' => 200
		];
		return $total;
	}	
}