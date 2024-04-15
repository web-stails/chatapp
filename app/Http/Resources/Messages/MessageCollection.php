<?php

namespace App\Http\Resources\Messages;

use App\Http\Resources\PaginationResource;

class MessageCollection extends PaginationResource
{
    public $collects = MessageResource::class;
}
