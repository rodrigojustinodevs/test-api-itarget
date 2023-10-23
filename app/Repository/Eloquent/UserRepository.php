<?php

namespace App\Repository\Eloquent;

use App\Exceptions\NotFoundException;
use App\Models\User;
use App\Repository\Contracts\{
    AbstractRepository,
    PaginationInterface,
    UserRepositoryInterface,
};
use App\Repository\Presenters\PaginationPresentr;
use Illuminate\Http\Response;

class UserRepository extends AbstractRepository implements UserRepositoryInterface
{

    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function findAll(): array
    {
        return $this->getModel()::get()->toArray();
    }

    public function paginate(int $page = 1): PaginationInterface
    {
        return new PaginationPresentr($this->getModel()::paginate());
    }

    public function showUser($field, $value): ?object
    {
        if(!$result = $this->getModel()::where($field, $value)->first()){
            throw new NotFoundException('User Not Found', Response::HTTP_NOT_FOUND);
        }

        return $result;
    }
}
