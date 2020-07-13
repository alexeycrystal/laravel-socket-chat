<?php


namespace App\Modules\Auth\Controllers;


use App\Entities\Request\Login\LoginEntity;
use App\Generics\Transformers\BaseDataResponseTransformer;
use App\Http\Controllers\APIController;
use App\Modules\Auth\Requests\LoginRequest;
use App\Modules\Auth\Services\AuthServiceContract;
use App\Modules\Auth\Transformers\LoginTransformer;
use Illuminate\Http\JsonResponse;

class LoginController extends APIController
{
    protected AuthServiceContract $authService;

    public function __construct(AuthServiceContract $authService)
    {
        $this->authService = $authService;
    }

    /**
     * @OA\Post(
     *     path="/auth/login",
     *     operationId="login",
     *     tags={"Authentication"},
     *     summary="User authentication endpoint to receive auth token.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/LoginEntity")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Successfull authorization.",
     *         @OA\JsonContent(
     *              ref="#/components/schemas/LoginResponseEntity"
     *         )
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Validation error. Invalid parameters."
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Some serious issue (with database / store) / server."
     *     )
     * )
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        $result = $this->authService
            ->login(
                new LoginEntity($credentials)
            );

        if ($result) {

            $transformed = LoginTransformer::transform($result);

            return response()->json(
                $transformed , 200
            );
        }

        if (!$result || $this->authService->hasErrors())
            return response()->json([
                'error' => 'Error occurs during the user login process!'
            ], 400);
    }
}
