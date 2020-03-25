<?php

namespace App\Models\Repositories\Abstracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

abstract class AbstractRepository implements RepositoryInterface
{
    /**
     * @return Builder
     */
    protected function getQuery() : Builder
    {
        /** @var Model $model */
        $model = $this->getModelName();
        return $model::query();
    }

    /**
     * @param array $data
     * @return Model
     */
    public function getNew(array $data = []) : Model
    {
        /** @var Model $model */
        $model = $this->getModelName();
        return  $model::create($data);
    }

    /**
     * @param callable $callback
     * @param bool $rawQuery
     * @return mixed
     */
    public function getCollection(callable $callback, bool $rawQuery = false)
    {
        $query = $callback($this->getQuery());
        return $rawQuery ? $query : $query->get();
    }
}
