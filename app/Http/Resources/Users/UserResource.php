<?php

namespace App\Http\Resources\Users;

use App\Http\Resources\Role\LiteRoleResource;
use Illuminate\Http\Resources\Json\JsonResource;


class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        /** @var \App\Models\User $resource */
        $resource = $this->resource;

        return [
            'userId'               => $resource->id,
            'email'            => $resource->email,
            'lastName'             => $resource->last_name,
            'firstName'          => $resource->first_name,
            'created_at'       => $resource->created_at,
            'updated_at'       => $resource->updated_at,
        ];
    }
}
