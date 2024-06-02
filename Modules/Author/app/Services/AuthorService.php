<?php

namespace Modules\Author\Services;

use App\Services\Cryptography\JsonWebToken;
use Modules\Author\Repositories\AuthorRepository;
use Modules\Author\Transformers\OutputAuthor;
use Modules\Author\Transformers\OutputAuthorCollection;

class AuthorService
{
    protected $authorRepository;

    public function __construct(AuthorRepository $authorRepository)
    {
        $this->authorRepository = $authorRepository;
    }

    public function getAllAuthors(): OutputAuthorCollection
    {
        return new OutputAuthorCollection($this->authorRepository->all());
    }

    public function getAuthorById(string $id): OutputAuthor
    {
        return new OutputAuthor(
            $this->authorRepository->find(JsonWebToken::decode($id))
        );
    }

    public function store(string $name, string $birth_date): OutputAuthor
    {
        $author = $this->authorRepository->create($name, $birth_date);
        return new OutputAuthor($author);
    }

    public function update(string $id, ?string $name, ?string $birth_date): OutputAuthor
    {
        return new OutputAuthor($this->authorRepository->update(
            JsonWebToken::decode($id), $name, $birth_date
        ));
    }

    public function delete(string $id): void
    {
        $this->authorRepository->delete(JsonWebToken::decode($id));
    }
}
