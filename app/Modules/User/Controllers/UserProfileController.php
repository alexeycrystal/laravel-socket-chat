<?php


namespace App\Modules\User\Controllers;


use App\Http\Controllers\Controller;
use App\Modules\User\Requests\GetProfileByLoggedUserRequest;
use App\Modules\User\Services\UserProfileServiceContract;

class UserProfileController extends Controller
{
    protected UserProfileServiceContract $userProfileService;

    public function __construct(UserProfileServiceContract $userProfileService)
    {
        $this->userProfileService = $userProfileService;
    }

    public function getProfileByLoggedUser(GetProfileByLoggedUserRequest $request)
    {
        $result = $this->userProfileService
            ->getUserProfileInfoByLoggedUser();

        if($result)
            return response()->json($result, 200);

        if(!$result || $this->userProfileService->hasErrors())
            return response()->json([
                'error' => 'Error occurs during the profile data receiving process.'
            ], 400);
    }
}
