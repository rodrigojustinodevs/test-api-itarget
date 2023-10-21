<?php

namespace App\Repository\Contracts;

use App\Exceptions\NotFoundException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;

abstract class AbstractRepository
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function __call($method, $attributes)
    {
        return $this->model->$method(...$attributes);
    }

    public function create(array $attributes): Model
    {
        return $this->model->create($attributes);
    }

    public function createMany(array $attributes): bool
    {
        return $this->model::insert($attributes);
    }

    public function find($id, array $columns = ['*']): ?Model
    {
        if (!$record = $this->model->find($id,$columns)) {
            throw new NotFoundException('Not Found', Response::HTTP_NOT_FOUND);
        }

        return $record;
    }

    public function firstOrFailByColumn(array $attributes, array $columns = ['*']): object
    {
        $record = $this->model
            ->where($attributes)
            ->first($columns);

        if (!$record) {
            throw new NotFoundException("Record not found", Response::HTTP_NOT_FOUND);
        }

        return $record;
    }

    public function update(array $attributes, array $options): Model
    {
        $record = $this->firstOrFailByColumn($attributes);
        $record->update($options);

        $record->refresh();


        return $record;
    }

    public function delete(array $attributes): bool
    {
        $record = $this->firstOrFailByColumn($attributes);

        return $record->delete();
    }

    public function increment(int $id, string $column, int $amount = 1, array $extra = []): int
    {
        return $this->model->find($id)->increment($column, $amount, $extra);
    }

    public function decrement(int $id, string $column, int $amount = 1, array $extra = []): int
    {
        return $this->model->find($id)->decrement($column, $amount, $extra);
    }

    public function all(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model::all();
    }

    public function save(Model $model): bool
    {
        return $model->save();
    }

    public function firstOrCreate(array $attributes, array $values): Model
    {
        return $this->model->firstOrCreate($attributes, $values);
    }

    public function updateById(int $id, array $values, string $primaryKey = 'id'): int
    {
        return $this->model->where($primaryKey, $id)->update($values);
    }
}
