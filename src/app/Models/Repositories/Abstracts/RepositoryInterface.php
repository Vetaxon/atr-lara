<?php


namespace App\Models\Repositories\Abstracts;


interface RepositoryInterface
{
    public function getModelName() : string;

    public function getNew(array $data = []);

    public function getCollection(callable $callback);
}
