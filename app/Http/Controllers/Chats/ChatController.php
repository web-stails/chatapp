<?php

namespace App\Http\Controllers\Chats;

use App\Builders\Chats\ChatBuilder;
use App\Exceptions\Chats\IsChatExistsException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Chats\ChatStoreRequest;
use App\Http\Resources\Chats\ChatCollection;
use App\Http\Resources\Chats\ChatResource;
use App\Models\Chat;
use App\Models\User;
use App\Services\Chats\ChatService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function __construct(private readonly ChatService $service)
    {
        $this->authorizeResource(Chat::class, 'chat');
    }

    /**
     * Display a listing of the resource.
     *
     * @param \App\Builders\Chats\ChatBuilder $builder
     *
     * @return \App\Http\Resources\Chats\ChatCollection
     */
    public function index(): ChatCollection
    {
        /** @var User $user */
        $user = Auth::user();

        $builder = new ChatBuilder($user);

        return ChatCollection::make($builder->handle());
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Chat $chat
     *
     * @return \App\Http\Resources\Chats\ChatResource
     */
    public function show(Chat $chat): ChatResource
    {
        return ChatResource::make($chat);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\Chats\ChatStoreRequest $request
     *
     * @return \App\Http\Resources\Chats\ChatResource
     */
    public function store(ChatStoreRequest $request, User $user): ChatResource
    {
        /** @var User $userAuthor */
        $userAuthor = Auth::user();

        try {
            $chat = $this->service->createChat(
                $request->validated(),
                $userAuthor,
                $user
            );
        } catch (IsChatExistsException $e) {
            $chat = $this->service->getChatFromUsers($userAuthor, $user);
        }


        return ChatResource::make($chat);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Chat $chat
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Chat $chat): Response
    {
        $this->service->delete($chat);

        return response()->noContent();
    }
}
