<?php

namespace Modules\Book\Tests\Unit;

use App\Services\Cryptography\JsonWebToken;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery;
use Modules\Book\Models\Book;
use Modules\Book\Repositories\BookRepository;
use Modules\Book\Services\BookService;
use Modules\Book\Transformers\OutputBook;
use Modules\Book\Transformers\OutputBookCollection;

class BookServiceTest extends TestCase
{
    use RefreshDatabase;

    protected BookService $bookService;
    protected $bookRepositoryMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->bookRepositoryMock = Mockery::mock(BookRepository::class);
        $this->bookService = new BookService($this->bookRepositoryMock);
    }

    public function testGetAllBooks(): void
    {
        $books = Book::factory()->count(5)->make();
        $paginator = new LengthAwarePaginator($books, 5, 10);
        $this->bookRepositoryMock->shouldReceive('all')->once()->andReturn($paginator);

        $result = $this->bookService->getAllBooks();

        $this->assertInstanceOf(OutputBookCollection::class, $result);
    }

    public function testGetBookById(): void
    {
        $book = Book::factory()->make(['id' => 1]);
        $bookId = JsonWebToken::encode($book->id);
        $this->bookRepositoryMock->shouldReceive('find')->once()->with($book->id)->andReturn($book);

        $result = $this->bookService->getBookById($bookId);

        $this->assertInstanceOf(OutputBook::class, $result);
    }

    public function testStore(): void
    {
        $book = Mockery::mock(Book::class)->makePartial();
        $book->title = 'Test Book';
        $book->year_of_publication = '2023';

        $authorIds = [JsonWebToken::encode(1), JsonWebToken::encode(2)];
        $decodedAuthorIds = [1, 2];

        $this->bookRepositoryMock->shouldReceive('create')->once()->with($book->title, $book->year_of_publication)->andReturn($book);

        $bookAuthorsMock = Mockery::mock(BelongsToMany::class);
        $book->shouldReceive('authors')->once()->andReturn($bookAuthorsMock);
        $bookAuthorsMock->shouldReceive('sync')->once()->with($decodedAuthorIds);

        $result = $this->bookService->store($book->title, $book->year_of_publication, $authorIds);

        $this->assertInstanceOf(OutputBook::class, $result);
    }

    public function testUpdate(): void
    {
        $book = Mockery::mock(Book::class)->makePartial();
        $book->id = 1;
        $book->title = 'Updated Test Book';
        $book->year_of_publication = '2023';
        
        $bookId = JsonWebToken::encode($book->id);
        $authorIds = [JsonWebToken::encode(1), JsonWebToken::encode(2)];
        $decodedAuthorIds = [1, 2];

        $this->bookRepositoryMock->shouldReceive('update')->once()->with($book->id, $book->title, $book->year_of_publication)->andReturn($book);

        $bookAuthorsMock = Mockery::mock(BelongsToMany::class);
        $book->shouldReceive('authors')->once()->andReturn($bookAuthorsMock);
        $bookAuthorsMock->shouldReceive('sync')->once()->with($decodedAuthorIds);

        $result = $this->bookService->update($bookId, $book->title, $book->year_of_publication, $authorIds);

        $this->assertInstanceOf(OutputBook::class, $result);
    }

    public function testDelete(): void
    {
        $book = Book::factory()->make(['id' => 1]);
        $bookId = JsonWebToken::encode($book->id);

        $this->bookRepositoryMock->shouldReceive('delete')->once()->with($book->id);

        $this->bookService->delete($bookId);

        $this->assertTrue(true);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
