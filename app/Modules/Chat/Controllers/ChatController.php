<?php


namespace App\Modules\Chat\Controllers;


use App\Http\Controllers\Controller;
use App\Modules\Chat\Requests\IndexChatRequest;
use App\Modules\Chat\Requests\StoreChatRequest;
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
     * @param IndexChatRequest $request
     * @return JsonResponse
     */
    public function index(IndexChatRequest $request)
    {
        $payload = $request->validated();

        $result = $this->chatService
            ->getChats($payload);

        if($result)
            return response()->json(
                ChatTransformer::transformChatIndex($payload, $result), 200
            );

        if(!$result || $this->chatService->hasErrors())
            return response()->json([
                'error' => 'Error occurs when receiving the list of user chats!'
            ], 400);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreChatRequest $request
     * @return JsonResponse
     */
    public function store(StoreChatRequest $request)
    {
        $payload = $request->validated();

        $result = $this->chatService
            ->createChatIfNotExists($payload['users_ids']);

        if($result) {

            $responseCode = $result['chat_already_exists']
                ? 200
                : 201;

            return response()->json(
                ChatTransformer::transformChatCreated($result), $responseCode
            );
        }

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
