<?php

namespace Modules\Book\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Book\Http\Requests\DestroyBookRequest;
use Modules\Book\Http\Requests\RegisterBookRequest;
use Modules\Book\Http\Requests\ShowBookRequest;
use Modules\Book\Http\Requests\UpdateBookRequest;
use Modules\Book\Services\BookService;

class BookController extends Controller
{
    protected $bookService;

    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }

    public function listAction(): JsonResponse
    {
        $output = $this->bookService->getAllBooks();
        return response()->json($output, JsonResponse::HTTP_OK);
    }

    public function storeAction(RegisterBookRequest $request): JsonResponse
    {
        $output = $this->bookService->store(
            $request->get('title'),
            $request->get('year_of_publication'),
            $request->get('author_ids')
        );
        return response()->json($output, JsonResponse::HTTP_CREATED);
    }

    public function showAction(ShowBookRequest $request): JsonResponse
    {
        $output = $this->bookService->getBookById($request->id);
        return response()->json($output, JsonResponse::HTTP_OK);
    }

    public function updateAction(UpdateBookRequest $request): JsonResponse
    {
        $output = $this->bookService->update(
            $request->id, 
            $request->get('title'),
            $request->get('year_of_publication'),
            $request->get('author_ids')
        );
        return response()->json($output, JsonResponse::HTTP_OK);
    }

    public function destroyAction(DestroyBookRequest $request): JsonResponse
    {
        $this->bookService->delete($request->id);
        return response()->json(status: JsonResponse::HTTP_NO_CONTENT);
    }
}
