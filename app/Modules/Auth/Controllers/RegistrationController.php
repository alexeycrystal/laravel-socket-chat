<?php


namespace App\Modules\Auth\Controllers;


use App\Entities\Request\Registration\RegistrationEntity;
use App\Generics\Transformers\BaseDataResponseTransformer;
use App\Http\Controllers\APIController;
use App\Modules\Auth\Requests\RegistrationRequest;
use App\Modules\Auth\Services\AuthServiceContract;
use Illuminate\Http\JsonResponse;

class RegistrationController extends APIController
{
    protected AuthServiceContract $authService;

    public function __construct(AuthServiceContract $authService)
    {
        $this->authService = $authService;
    }

    /**
     * @OA\Post(
     *     path="/auth/registration",
     *     operationId="registration",
     *     tags={"Authentication"},
     *     summary="New user registration.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/RegistrationEntity")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Successfull registration.",
     *         @OA\JsonContent(
     *              ref="#/components/schemas/RegistrationResponseEntity"
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
     * @param RegistrationRequest $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function registration(RegistrationRequest $request)
    {
        $payload = $request->validated();

        $result = $this->authService
            ->registration(
                new RegistrationEntity($payload)
            );

        if($result)
            return response()->json(
                BaseDataResponseTransformer::transform($result), 201
            );

        if(!$result || $this->authService->hasErrors())
            return response()->json([
                'error' => 'Error occurs during the user registration process!'
            ], 400);
    }
}
