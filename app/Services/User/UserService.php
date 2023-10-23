<?php

declare(strict_types=1);

namespace App\Services\User;

use App\Http\Resources\UserResouce;
use App\Repository\Eloquent\UserRepository;
use Illuminate\Support\Facades\DB;




class UserService
{

    protected $repository;


    public function __construct(
        UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function showAllUser()
    {
        $respose = $this->repository->paginate();

         return UserResouce::collection(collect($respose->items()))
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
            return new UserResouce($user);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function show($id)
    {
        $response = $this->repository->showUser('id', $id);

        return new UserResouce($response);
    }

    public function update(array $payload, $id)
    {
        DB::beginTransaction();

        try {
            $response = $this->repository->update(['id' => $id], $payload);
            DB::commit();
            return new UserResouce($response);
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
