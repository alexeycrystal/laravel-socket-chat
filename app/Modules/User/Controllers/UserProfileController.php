<?php


namespace App\Modules\User\Controllers;


use App\Generics\Transformers\BaseDataResponseTransformer;
use App\Http\Controllers\Controller;
use App\Modules\User\Requests\ChangePasswordRequest;
use App\Modules\User\Requests\GetProfileByLoggedUserRequest;
use App\Modules\User\Requests\Status\UpdateUserStatusRequest;
use App\Modules\User\Requests\UserProfileUpdateRequest;
use App\Modules\User\Services\UserProfileServiceContract;
use App\Modules\User\Transformers\UserPasswordChangeTransformer;
use App\Modules\User\Transformers\UserProfileTransformer;
use App\Modules\User\Transformers\UserProfileUpdateTransformer;

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
            return response()->json(
                UserProfileTransformer::transform($result), 200
            );

        if(!$result || $this->userProfileService->hasErrors())
            return response()->json([
                'error' => 'Error occurs during the profile data receiving process.'
            ], 400);
    }

    public function updatePassword(ChangePasswordRequest $request)
    {
        $payload = $request->validated();

        $result = $this->userProfileService
            ->changePassword($payload['password']);

        if($result)
            return response()->json(
                UserPasswordChangeTransformer::transform($result), 200
            );

        if(!$result || $this->userProfileService->hasErrors())
            return response()->json([
                'error' => 'Error occurs during the password change process.'
            ], 400);
    }

    public function updateProfileSettings(UserProfileUpdateRequest $request)
    {
        $payload = $request->validated();

        $result = $this->userProfileService
            ->updateProfileSettings($payload);

        if($result)
            return response()->json(
                UserProfileUpdateTransformer::transform($result), 202
            );

        if(!$result || $this->userProfileService->hasErrors())
            return response()->json([
                'error' => 'Error occurs during the password change process.'
            ], 400);
    }

    public function updateUserStatus(UpdateUserStatusRequest $request)
    {
        $payload = $request->validated();

        $result = $this->userProfileService
            ->changeLoggedUserStatus($payload['status']);

        if($result)
            return response()->json(
                UserProfileUpdateTransformer::transform($result), 202
            );

        if(!$result || $this->userProfileService->hasErrors())
            return response()->json([
                'error' => 'Error occurs during the status change process.'
            ], 400);
    }
}
