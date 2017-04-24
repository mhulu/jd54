<?php

namespace App\Http\Controllers;

use App\Repository\Eloquent\SiteRepo;
use App\Repository\Eloquent\UserRepo;
use App\Transformers\UserinfoTransformer;
use Illuminate\Http\Request;

class HomeController extends Controller
{
	public function index()
	{
		return view('app');
	}
}
