<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SignupFormRequest;

class AuthController extends Controller
{

	public function signup(SignupFormRequest $request)
	{
		return $request->persist();
	}
}
