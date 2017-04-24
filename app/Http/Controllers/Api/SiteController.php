<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repository\Eloquent\SiteRepo;
use Illuminate\Http\Request;

class SiteController extends Controller
{
	protected $site;
	public function __construct(SiteRepo $site)
	{
		$this->site = $site;
	}
    public function statistics()
    {
    	return $this->site->getStatistics();
    }
}
