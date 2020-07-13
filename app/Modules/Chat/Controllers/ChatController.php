<?php


namespace App\Modules\Chat\Controllers;


use App\Http\Controllers\APIController;
use App\Modules\Chat\Entities\ChatIndexEntity;
use App\Modules\Chat\Requests\DestroyChatRequest;
use App\Modules\Chat\Requests\IndexChatRequest;
use App\Modules\Chat\Requests\ShowChatRequest;
use App\Modules\Chat\Requests\StoreChatRequest;
use App\Modules\Chat\Services\ChatServiceContract;
use App\Modules\Chat\Transformers\ChatTransformer;
use Illuminate\Http\JsonResponse;

/**
 * @OA\SecurityScheme(
 *      securityScheme="bearerAuth",
 *      type="http",
 *      scheme="bearer",
 *      bearerFormat="JWT"
 * )
 */
class ChatController extends APIController
{
    protected ChatServiceContract $chatService;

    public function __construct(ChatServiceContract $chatService)
    {
        $this->chatService = $chatService;
    }

    /**
     * @OA\Get(
     *     path="/user/chats",
     *     operationId="chatIndex",
     *     tags={"Chat"},
     *     summary="User authentication endpoint to receive auth token.",
     *     security = {{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Elements per page limiter",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int32",
     *             minimum=1,
     *             example=10
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number to select",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int32",
     *             minimum=1,
     *             example=10
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="filter",
     *         in="query",
     *         description="Filter value to search chats by messages",
     *         required=false,
     *         example=10,
     *         @OA\Schema(
     *             type="string",
     *             example="my message"
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Received chats with no errors.",
     *         @OA\JsonContent(
     *              ref="#/components/schemas/ChatIndexResponseEntity"
     *         )
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Unauthorized error. Invalid token or bearer token not presented.",
     *         @OA\JsonContent(
     *              ref="#/components/schemas/UnauthorizedResponseEntity"
     *         )
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Validation error. Invalid parameters."
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Some serious issue (with database / store) / server.",
     *         @OA\JsonContent(
     *              ref="#/components/schemas/SeriousServerErrorResponseEntity"
     *         )
     *     )
     * )
     *
     * @param IndexChatRequest $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function index(IndexChatRequest $request)
    {
        $payload = $request->validated();

        $payload = new ChatIndexEntity($payload);

        $result = $this->chatService
            ->getChats($payload);

        if ($this->chatService->hasErrors())
            return response()->json([
                'error' => 'Error occurs when receiving the list of user chats!'
            ], 400);

        return response()->json(
            ChatTransformer::transformChatIndex($payload, $result), 200
        );
    }

    public function show(ShowChatRequest $request, $id)
    {
        $payload = $request->validated();

        $result = $this->chatService
            ->showChat($payload['id']);

        if ($this->chatService->hasErrors())
            return response()->json([
                'error' => 'Error occurs when receiving the list of user chats!'
            ], 400);

        return response()->json(
            ChatTransformer::transformShowChat($result), 200
        );
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

        if ($result) {

            $responseCode = $result['chat_already_exists']
                ? 200
                : 201;

            return response()->json(
                ChatTransformer::transformChatCreated($result), $responseCode
            );
        }

        if (!$result || $this->chatService->hasErrors())
            return response()->json([
                'error' => 'Error occurs during the chat creation process!'
            ], 400);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyChatRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(DestroyChatRequest $request, $id)
    {
        $result = $this->chatService
            ->hideChatAndClearHistory($id);

        if ($result)
            return response()->json(
                ChatTransformer::chatDeleteSuccess($result), 204
            );

        if (!$result || $this->chatService->hasErrors())
            return response()->json([
                'error' => 'Error occurs when receiving the list of user chats!'
            ], 400);
    }
}
