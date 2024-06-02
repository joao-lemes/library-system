<?php

namespace Modules\User\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\User\Models\User;

class UserRepository
{
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }
    
    public function all(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->paginate($perPage);
    }

    public function create(array $data): User
    {
        return $this->model->create($data);
    }
}
