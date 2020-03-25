<?php

namespace App\Services\Responses\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\HttpFoundation\StreamedResponse;

interface StreamResponsesFactoryInterface
{
    public function getCsvQueryStream(Builder $query, array $tableMap, array $options = []) : StreamedResponse;
}
