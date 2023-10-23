<?php

namespace App\Repository\Eloquent;

use App\Exceptions\NotFoundException;
use App\Models\Registration\Registration;
use App\Repository\Contracts\{
    AbstractRepository,
    PaginationInterface,
    RegistrationRepositoryInterface,
};
use App\Repository\Presenters\PaginationPresentr;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class RegistrationRepository extends AbstractRepository implements RegistrationRepositoryInterface
{

    public function __construct(Registration $model)
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

    public function showRegistrationConflit($field, $value): ?object
    {

        return $this->getModel()::select(
            "events.start_date",
            "events.end_date",
            "registrations.*"
            )
            ->join('events', 'events.id', 'registrations.event_id')
            ->where('registrations.'.$field, $value)
            ->get();
    }

    public function showRegistration($field, $value): ?object
    {
        if(!$result = $this->getModel()::where($field, $value)->first()){
            throw new NotFoundException('Event Not Found', Response::HTTP_NOT_FOUND);
        }

        return $result;
    }
}
