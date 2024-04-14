<?php

namespace App\Http\Resources\Chats;

use App\Http\Resources\PaginationResource;

class ChatCollection extends PaginationResource
{
    public $collects = ChatResource::class;
}
