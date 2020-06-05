<?php


namespace App\Modules\Auth\Controllers;


use App\Http\Controllers\Controller;
use App\Modules\Auth\Requests\LoginRequest;
use App\Modules\Auth\Services\AuthServiceContract;

class LoginController extends Controller
{
    protected AuthServiceContract $authService;

    public function __construct(AuthServiceContract $authService)
    {
        $this->authService = $authService;
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        $result = $this->authService
            ->login($credentials);

        if($result)
            return response()->json($result, 200);

        if(!$result || $this->authService->hasErrors())
            return response()->json([
                'error' => 'Error occurs during the user login process!'
            ], 400);
    }
}
