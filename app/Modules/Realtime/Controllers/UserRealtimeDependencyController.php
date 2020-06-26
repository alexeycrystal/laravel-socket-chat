<?php


namespace App\Modules\Realtime\Controllers;


use App\Http\Controllers\Controller;
use App\Modules\Realtime\Requests\DestroyUserDependencyRequest;
use App\Modules\Realtime\Requests\StoreUserRealtimeDependencyRequest;
use App\Modules\Realtime\Services\UserRealtimeDependencyServiceContract;
use App\Modules\Realtime\Transformers\UserRealtimeDependencyTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserRealtimeDependencyController extends Controller
{
    protected UserRealtimeDependencyServiceContract $userRealtimeDependencyService;

    public function __construct(UserRealtimeDependencyServiceContract $userRealtimeDependencyService)
    {
        $this->userRealtimeDependencyService = $userRealtimeDependencyService;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreUserRealtimeDependencyRequest $request
     * @return JsonResponse
     */
    public function store(StoreUserRealtimeDependencyRequest $request)
    {
        $payload = $request->validated();

        $result = $this->userRealtimeDependencyService
            ->addLoggedUserAsListener($payload['chats_ids']);

        if (!isset($result)
            || $this->userRealtimeDependencyService->hasErrors())
            return response()->json([
                'error' => 'Error occurs when receiving updating realtime listeners!'
            ], 400);

        return response()->json(
            UserRealtimeDependencyTransformer::transformStoreResult($result), 201
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyUserDependencyRequest $request
     * @param int $id
     */
    public function destroy(DestroyUserDependencyRequest $request, $id)
    {
        $result = $this->userRealtimeDependencyService
            ->removeLoggedUserFromListeners();

        if (!isset($result)
            || $this->userRealtimeDependencyService->hasErrors())
            return response()->json([
                'error' => 'Error occurs while destroying ws-realtime listeners!'
            ], 400);

        return response()->json(
            UserRealtimeDependencyTransformer::transformDestroyResult($result), 200
        );
    }
}
