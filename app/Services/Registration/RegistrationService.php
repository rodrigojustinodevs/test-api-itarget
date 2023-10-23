<?php

declare(strict_types=1);

namespace App\Services\Registration;

use App\Exceptions\NotFoundException;
use App\Http\Resources\RegistrationResouce;
use App\Repository\Eloquent\EventRepository;
use App\Repository\Eloquent\RegistrationRepository;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Response;

class RegistrationService
{

    protected $repository;
    protected $repositoryEvent;


    public function __construct(
        RegistrationRepository $repository,
        EventRepository $repositoryEvent)
    {
        $this->repository = $repository;
        $this->repositoryEvent = $repositoryEvent;
    }

    public function showAllRegistration()
    {
        $respose = $this->repository->paginate();

         return RegistrationResouce::collection(collect($respose->items()))
                        ->additional([
                            'pagination' => [
                                'total' => $respose->total(),
                                'current_page' => $respose->currentPage(),
                                'last_page' => $respose->lastPage(),
                                'first_page' => $respose->firstPage(),
                                'per_page' => $respose->perPage(),
                            ]
                        ]);
    }

    public function created(array $payload)
    {
        DB::beginTransaction();

        try {


            $this->notDateConflicts($payload);
            $registration = $this->repository->create($payload);
            DB::commit();
            return new RegistrationResouce($registration);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function show($id)
    {
        $response = $this->repository->showRegistration('id', $id);

        return new RegistrationResouce($response);
    }

    public function update(array $payload, $id)
    {
        DB::beginTransaction();
        $this->notDateConflicts($payload, $id);

        try {
            $response = $this->repository->update(['id' => $id], $payload);
            DB::commit();
            return new RegistrationResouce($response);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {

            $response = $this->repository->delete(['id' => $id]);
            DB::commit();
            return $response;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    private function notDateConflicts (array $payload, $id = null) {

        $registratiUser = $this->repository->showRegistrationConflit('user_id', $payload['user_id']);

        $registratiEvent = $this->repositoryEvent->showEvent('id', $payload['event_id']);

        if (!empty($registratiUser) && !empty($registratiEvent)) {
            foreach ($registratiUser as $userRegistration) {
                $startDateUser = Carbon::parse($userRegistration->start_date);
                $endDateUser = Carbon::parse($userRegistration->end_date)->toDateTimeString();

                $startDateEvent = Carbon::parse($registratiEvent->start_date)->toDateTimeString();
                $endDateEvent = Carbon::parse($registratiEvent->end_date)->toDateTimeString();
                if (
                    ($startDateUser <= $endDateEvent && $endDateUser >= $startDateEvent) ||
                    ($startDateUser >= $startDateEvent && $endDateUser <= $endDateEvent)
                ) {
                    throw new NotFoundException('Esse evento entra em conflito com outros eventos já registrados pelo usuário.', Response::HTTP_CONFLICT);
                }
            }
        }
    }
}
