<?php


namespace App\Modules\Auth\Controllers;


use App\Http\Controllers\Controller;
use App\Modules\Auth\Requests\RegistrationRequest;
use App\Modules\Auth\Services\AuthServiceContract;

class RegistrationController extends Controller
{
    protected AuthServiceContract $authService;

    public function __construct(AuthServiceContract $authService)
    {
        $this->authService = $authService;
    }

    public function registration(RegistrationRequest $request)
    {
        $payload = $request->validated();

        $result = $this->authService
            ->registration($payload);

        if($result)
            return response()->json($result, 201);

        if(!$result || $this->authService->hasErrors())
            return response()->json([
                'error' => 'Error occurs during the user registration process!'
            ], 400);
    }
}
