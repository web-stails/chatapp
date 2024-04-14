<?php

namespace App\Http\Resources\Auth\Restore;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResetTokenResource extends JsonResource
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
        return parent::toArray($request);
    }
}