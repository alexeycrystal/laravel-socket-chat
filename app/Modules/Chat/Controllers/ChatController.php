<?php


namespace App\Modules\Chat\Controllers;


use App\Http\Controllers\Controller;
use App\Modules\Chat\Requests\CreateChatRequest;
use App\Modules\Chat\Services\ChatServiceContract;
use App\Modules\Chat\Transformers\ChatTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    protected ChatServiceContract $chatService;

    public function __construct(ChatServiceContract $chatService)
    {
        $this->chatService = $chatService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateChatRequest $request
     * @return JsonResponse
     */
    public function store(CreateChatRequest $request)
    {
        $payload = $request->validated();

        $result = $this->chatService
            ->createChat($payload['users_ids']);

        if($result)
            return response()->json(
                ChatTransformer::transformChatCreated($result), 201
            );

        if(!$result || $this->chatService->hasErrors())
            return response()->json([
                'error' => 'Error occurs during the chat creation process!'
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        //
    }
}
