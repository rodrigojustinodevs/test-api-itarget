<?php

namespace App\Repository\Eloquent;

use App\Exceptions\NotFoundException;
use App\Models\Event\Event;
use App\Repository\Contracts\{
    AbstractRepository,
    PaginationInterface,
    EventRepositoryInterface,
};
use App\Repository\Presenters\PaginationPresentr;
use Illuminate\Http\Response;

class EventRepository extends AbstractRepository implements EventRepositoryInterface
{

    public function __construct(Event $model)
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

    public function showEvent($field, $value): ?object
    {
        if(!$result = $this->getModel()::where($field, $value)->first()){
            throw new NotFoundException('Event Not Found', Response::HTTP_NOT_FOUND);
        }

        return $result;
    }
}
