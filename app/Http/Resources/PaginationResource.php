<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PaginationResource extends ResourceCollection
{
    public static $wrap = 'data';

    public function __construct($query, string $collects = '')
    {
        $totalCount = $query->count();
        $result = $query->paginate(20);
        $this->additional(compact('totalCount'));
        $this->collects = $this->collects ?? $collects;

        parent::__construct($result);
    }
}