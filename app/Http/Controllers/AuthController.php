<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterUserRequest;
use App\Services\RegisterService;

class AuthController extends Controller
{
    public function register(RegisterUserRequest $request, RegisterService $registerService)
    {
        $registerService->register($request);
    }
}
