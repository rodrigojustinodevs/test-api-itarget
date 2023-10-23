<?php

declare(strict_types=1);

namespace App\Services\Event;

use App\Http\Resources\EventResouce;
use App\Repository\Eloquent\EventRepository;
use Illuminate\Support\Facades\DB;




class EventService
{

    protected $repository;


    public function __construct(
        EventRepository $repository)
    {
        $this->repository = $repository;
    }

    public function showAllEvent()
    {
        $respose = $this->repository->paginate();

         return EventResouce::collection(collect($respose->items()))
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
            $user = $this->repository->create($payload);
            DB::commit();
            return new EventResouce($user);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function show($id)
    {
        $response = $this->repository->showEvent('id', $id);

        return new EventResouce($response);
    }

    public function update(array $payload, $id)
    {
        DB::beginTransaction();

        try {
            $response = $this->repository->update(['id' => $id], $payload);
            DB::commit();
            return new EventResouce($response);
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

}
