<?php

namespace App\Http\Resources\Messages;

use App\Http\Resources\Chats\ChatResource;
use App\Http\Resources\Users\UserResource;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
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
        /** @var Message $resource */
        $resource = $this->resource;

        return [
            'messageId'  => $resource->id,
            'text'       => $resource->text,
            'user'       => UserResource::make($this->whenLoaded('user')),
            'timestamp'  => $resource->created_at,
            'created_at' => $resource->created_at,
            'updated_at' => $resource->updated_at,
        ];
    }
}
