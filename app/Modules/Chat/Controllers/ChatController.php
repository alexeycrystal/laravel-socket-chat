<?php


namespace App\Modules\Chat\Controllers;


use App\Http\Controllers\APIController;
use App\Modules\Chat\Entities\Index\ChatIndexEntity;
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
     *     summary="Endpoint to receive chat list by parameters.",
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

    /**
     * @OA\Get(
     *     path="/user/chats/{chat_id}",
     *     operationId="chatShow",
     *     tags={"Chat"},
     *     summary="Get chat by id.",
     *     security = {{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="chat_id",
     *         in="path",
     *         description="Chat id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64",
     *             minimum=1,
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Received chats with no errors.",
     *         @OA\JsonContent(
     *              ref="#/components/schemas/ChatShowResponseEntity"
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
     * @param ShowChatRequest $request
     * @param $id
     * @return JsonResponse
     */
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
     * @OA\Post(
     *     path="/user/chats/{chat_id}",
     *     operationId="chatShow",
     *     tags={"Chat"},
     *     summary="Get chat by id.",
     *     security = {{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="users_ids",
     *                     description="User ids to add to the chat",
     *                     type="array",
     *                     @OA\Items(
     *                          type="integer",
     *                          minimum=1
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Received chats with no errors.",
     *         @OA\JsonContent(
     *              ref="#/components/schemas/ChatShowResponseEntity"
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
     * @param StoreChatRequest $request
     * @return JsonResponse
     */
    public function store(StoreChatRequest $request)
    {
        $payload = $request->validated();

        $result = $this->chatService
            ->createChatIfNotExists($payload['users_ids']);

        if ($result) {

            $responseCode = $result->chat_already_exists
                ? 200
                : 201;

            $transformed = ChatTransformer::transformChatCreated($result);

            echo json_encode($transformed);
            die();

            return response()->json(
                $transformed, $responseCode
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
