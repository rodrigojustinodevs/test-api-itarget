<?php

namespace App\Repository\Contracts;

interface EventRepositoryInterface
{
    public function findAll(): array;
    public function paginate(int $page = 1): PaginationInterface;
    public function create(array $dat): object;
    public function firstOrCreate(array $attributes, array $values): object;
    public function update(array $attributes, array $options): object;
    public function delete(array $attributes): bool;
    public function find(int $id, array $columns = ['*']): ?object;

}
