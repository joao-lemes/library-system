<?php

namespace Modules\Author\Tests\Unit;

use App\Services\Cryptography\JsonWebToken;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery;
use Modules\Author\Models\Author;
use Modules\Author\Repositories\AuthorRepository;
use Modules\Author\Services\AuthorService;
use Modules\Author\Transformers\OutputAuthor;
use Modules\Author\Transformers\OutputAuthorCollection;

class AuthorServiceTest extends TestCase
{
    use RefreshDatabase;

    protected AuthorService $authorService;
    protected $authorRepositoryMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->authorRepositoryMock = Mockery::mock(AuthorRepository::class);
        $this->authorService = new AuthorService($this->authorRepositoryMock);
    }

    public function testGetAllAuthors()
    {
        $authors = Author::factory()->count(5)->make();
        $paginator = new LengthAwarePaginator($authors, 5, 10);
        $this->authorRepositoryMock->shouldReceive('all')->once()->andReturn($paginator);

        $result = $this->authorService->getAllAuthors();

        $this->assertInstanceOf(OutputAuthorCollection::class, $result);
    }

    public function testGetAuthorById()
    {
        $author = Author::factory()->make(['id' => 1]);
        $authorId = JsonWebToken::encode($author->id);
        $this->authorRepositoryMock->shouldReceive('find')->once()->with($author->id)->andReturn($author);

        $result = $this->authorService->getAuthorById($authorId);

        $this->assertInstanceOf(OutputAuthor::class, $result);
    }

    public function testStore()
    {
        $author = Author::factory()->make();
        $this->authorRepositoryMock->shouldReceive('create')->once()->with($author->name, $author->birth_date)->andReturn($author);

        $result = $this->authorService->store($author->name, $author->birth_date);

        $this->assertInstanceOf(OutputAuthor::class, $result);
    }

    public function testUpdate()
    {
        $author = Author::factory()->make(['id' => 1]);
        $authorId = JsonWebToken::encode($author->id);
        $this->authorRepositoryMock->shouldReceive('update')->once()->with($author->id, $author->name, $author->birth_date)->andReturn($author);

        $result = $this->authorService->update($authorId, $author->name, $author->birth_date);

        $this->assertInstanceOf(OutputAuthor::class, $result);
    }

    public function testDelete()
    {
        $author = Author::factory()->make(['id' => 1]);
        $authorId = JsonWebToken::encode($author->id);
        $this->authorRepositoryMock->shouldReceive('delete')->once()->with($author->id);

        $this->authorService->delete($authorId);

        $this->assertTrue(true);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
