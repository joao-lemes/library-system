<?php

namespace Modules\Book\Repositories;

use App\Exceptions\NotFoundException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Book\Models\Book;

class BookRepository
{
    protected $model;

    public function __construct(Book $model)
    {
        $this->model = $model;
    }

    public function all(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->paginate($perPage);
    }

    public function find(int $id): ?Book
    {
        return $this->model->find($id);
    }

    public function create(string $title = null, string $yearOfPublication = null): Book
    {
        return $this->model->create([
            'title' => $title,
            'year_of_publication' => $yearOfPublication,
        ]);
    }

    public function update(
        int $id, 
        ?string $title = null, 
        ?string $yearOfPublication = null
    ): Book {
        if (!$book = $this->model->find($id)) {
            throw new NotFoundException(trans('exception.not_found.book'));
        }

        $data = array_filter([
            'title' => $title,
            'year_of_publication' => $yearOfPublication
        ], fn($value) => !is_null($value));
        $book->update($data);

        return $book;
    }

    public function delete(int $id): ?bool
    {
        if (!$book = $this->model->find($id)) {
            throw new NotFoundException(trans('exception.not_found.book'));
        }
        return $book->delete();
    }
}
