<?php

namespace App\Repositories\Contracts;

use Illuminate\Support\Collection;

interface StreamingServiceRepositoryInterface
{
    public function allOrderedByName(): Collection;
}
