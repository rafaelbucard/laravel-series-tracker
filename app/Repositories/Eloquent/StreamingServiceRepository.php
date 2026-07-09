<?php

namespace App\Repositories\Eloquent;

use App\Models\StreamingService;
use App\Repositories\Contracts\StreamingServiceRepositoryInterface;
use Illuminate\Support\Collection;

class StreamingServiceRepository implements StreamingServiceRepositoryInterface
{
    public function allOrderedByName(): Collection
    {
        return StreamingService::orderBy('name')->get();
    }
}
