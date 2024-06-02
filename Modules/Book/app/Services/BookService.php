<?php

namespace Modules\Book\Services;

use App\Services\Cryptography\JsonWebToken;
use Modules\Book\Repositories\BookRepository;
use Modules\Book\Transformers\OutputBook;
use Modules\Book\Transformers\OutputBookCollection;

class BookService
{
    protected BookRepository $bookRepository;

    public function __construct(BookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    public function getAllBooks(): OutputBookCollection
    {
        return new OutputBookCollection($this->bookRepository->all());
    }

    public function getBookById(string $id): OutputBook
    {
        return new OutputBook(
            $this->bookRepository->find(JsonWebToken::decode($id))
        );
    }

    /** @var array<string> $authorIds */
    public function store(
        string $title, 
        string $yearOfPublication, 
        array $authorIds
    ): OutputBook {
        $book = $this->bookRepository->create($title, $yearOfPublication);
        $decodedAuthorIds = array_map(fn($authorId) => JsonWebToken::decode($authorId), $authorIds);
        $book->authors()->sync($decodedAuthorIds);

        return new OutputBook($book);
    }

    /** @var ?array<string> $authorIds */
    public function update(
        string $id, 
        ?string $title, 
        ?string $yearOfPublication, 
        ?array $authorIds
    ): OutputBook {
        $book = $this->bookRepository->update(
            JsonWebToken::decode($id), $title, $yearOfPublication
        );

        if ($authorIds) {
            $decodedAuthorIds = array_map(fn($authorId) => JsonWebToken::decode($authorId), $authorIds);
            $book->authors()->sync($decodedAuthorIds);
        }

        return new OutputBook($book);
    }

    public function delete(string $id): void
    {
        $this->bookRepository->delete(JsonWebToken::decode($id));
    }
}
