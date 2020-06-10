<?php


namespace App\Modules\Message\Controllers;


use App\Generics\Transformers\BaseDataResponseTransformer;
use App\Http\Controllers\Controller;
use App\Modules\Message\Requests\StoreMessageRequest;
use App\Modules\Message\Services\MessageServiceContract;
use App\Modules\Message\Transformers\MessageTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    protected MessageServiceContract $messageService;

    public function __construct(MessageServiceContract $messageService)
    {
        $this->messageService = $messageService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreMessageRequest $request
     * @return JsonResponse
     */
    public function store(StoreMessageRequest $request)
    {
        $payload = $request->validated();

        $result = $this->messageService
            ->createByLoggedUser($payload);

        if($result)
            return response()->json(
                MessageTransformer::transformMessageStore($result), 201
            );

        if(!$result || $this->messageService->hasErrors())
            return response()->json([
                'error' => 'Error occurs during the message creation process!'
            ], 400);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
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
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
