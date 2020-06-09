<?php


namespace App\Modules\User\Controllers;


use App\Http\Controllers\Controller;
use App\Modules\User\Requests\UserContact\IndexUserContactsRequest;
use App\Modules\User\Requests\UserContact\StoreUserContactRequest;
use App\Modules\User\Services\UserContactsServiceContract;
use App\Modules\User\Transformers\UserContact\UserContactTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserContactController extends Controller
{
    protected UserContactsServiceContract $userContactsService;

    public function __construct(UserContactsServiceContract $userContactsService)
    {
        $this->userContactsService = $userContactsService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param IndexUserContactsRequest $request
     * @return JsonResponse
     */
    public function index(IndexUserContactsRequest $request)
    {
        $params = $request->validated();

        $result = $this->userContactsService
            ->getContactsByParams($params);

        if($this->userContactsService->hasErrors())
            return response()->json([
                'error' => 'Error occurs during the contacts receiving process.'
            ], 400);

        return response()->json(
            UserContactTransformer::transformContactIndex($params, $result), 200
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return JsonResponse
     */
    public function store(StoreUserContactRequest $request)
    {
        $payload = $request->validated();

        $result = $this->userContactsService
            ->addContactToLoggedUser($payload['contact_user_id']);

        if($result)
            return response()->json(
                UserContactTransformer::transformContactStore($result), 201
            );

        if(!$result || $this->userContactsService->hasErrors())
            return response()->json([
                'error' => 'Error occurs during the contact creation process.'
            ], 400);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        //
    }
}
