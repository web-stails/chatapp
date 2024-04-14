<?php

namespace App\Http\Resources\Users;

use App\Http\Resources\PaginationResource;

class UserCollection extends PaginationResource
{
    public $collects = UserResource::class;
}
