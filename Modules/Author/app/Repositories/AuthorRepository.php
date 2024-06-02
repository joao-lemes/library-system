<?php

namespace Modules\Author\Repositories;

use App\Exceptions\NotFoundException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Author\Models\Author;

class AuthorRepository
{
    protected $model;

    public function __construct(Author $model)
    {
        $this->model = $model;
    }

    public function all(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->paginate($perPage);
    }

    public function find(int $id): ?Author
    {
        return $this->model->find($id);
    }

    public function create(string $name = null, string $birthDate = null): Author
    {
        return $this->model->create([
            'name' => $name,
            'birth_date' => $birthDate,
        ]);
    }

    public function update(
        int $id, 
        ?string $name = null, 
        ?string $birthDate = null
    ): Author {
        if (!$author = $this->model->find($id)) {
            throw new NotFoundException(trans('exception.not_found.author'));
        }
        
        $data = array_filter([
            'name' => $name,
            'birth_date' => $birthDate
        ], fn($value) => !is_null($value));
        $author->update($data);

        return $author;
    }

    public function delete(int $id): ?bool
    {
        if (!$author = $this->model->find($id)) {
            throw new NotFoundException(trans('exception.not_found.author'));
        }
        return $author->delete();
    }
}
