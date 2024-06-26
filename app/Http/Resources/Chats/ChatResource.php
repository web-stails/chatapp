<?php

namespace App\Http\Resources\Chats;

use App\Http\Resources\Users\UserResource;
use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Chat $resource */
        $resource = $this->resource;

        return [
            'chatId'     => $resource->id,
            'title'      => $resource->title,
            'user'       => UserResource::make($this->whenLoaded('user')),
            'timestamp'  => $resource->timestamp,
            'created_at' => $resource->created_at,
            'updated_at' => $resource->updated_at,
        ];
    }
}
