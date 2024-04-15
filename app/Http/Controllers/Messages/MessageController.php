<?php

namespace App\Http\Controllers\Messages;

use App\Builders\Messages\MessageBuilder;
use App\Http\Controllers\Controller;
use App\Http\Requests\Messages\MessageStoreRequest;
use App\Http\Requests\Messages\MessageUpdateRequest;
use App\Http\Resources\Messages\MessageCollection;
use App\Http\Resources\Messages\MessageResource;
use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use App\Services\Messages\MessageService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function __construct(private readonly MessageService $service)
    {
        $this->authorizeResource(Message::class, 'message');
    }

    /**
     * Display a listing of the resource.
     *
     * @param \App\Builders\Messages\MessageBuilder $builder
     *
     * @return \App\Http\Resources\Messages\MessageCollection
     */
    public function index(Chat $chat): MessageCollection
    {
        $builder = new MessageBuilder($chat);

        return MessageCollection::make($builder->handle());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\Messages\MessageStoreRequest $request
     *
     * @return \App\Http\Resources\Messages\MessageResource
     */
    public function store(MessageStoreRequest $request, Chat $chat): MessageResource
    {
        /** @var User $user */
        $user = Auth::user();

        $message = $this->service->createMessage($request, $chat, $user);

        return MessageResource::make(MessageService::loadRelation($message));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\Messages\MessageUpdateRequest $request
     *
     * @return \App\Http\Resources\Messages\MessageResource
     */
    public function update(MessageUpdateRequest $request, Chat $chat, Message $message): MessageResource
    {
        $message = $this->service->update($message, $request->validated());

        return MessageResource::make(MessageService::loadRelation($message));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Message $message
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Chat $chat, Message $message): Response
    {
        $this->service->delete($message);

        return response()->noContent();
    }
}
