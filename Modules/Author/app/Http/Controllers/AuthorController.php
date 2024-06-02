<?php

namespace Modules\Author\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Author\Http\Requests\DestroyAuthorRequest;
use Modules\Author\Http\Requests\RegisterAuthorRequest;
use Modules\Author\Http\Requests\ShowAuthorRequest;
use Modules\Author\Http\Requests\UpdateAuthorRequest;
use Modules\Author\Services\AuthorService;

class AuthorController extends Controller
{
    protected AuthorService $authorService;

    public function __construct(AuthorService $authorService)
    {
        $this->authorService = $authorService;
    }

    public function listAction(): JsonResponse
    {
        $output = $this->authorService->getAllAuthors();
        return response()->json($output, JsonResponse::HTTP_OK);
    }

    public function storeAction(RegisterAuthorRequest $request): JsonResponse
    {        
        $output = $this->authorService->store(
            $request->get('name'),
            $request->get('birth_date')
        );
        return response()->json($output, JsonResponse::HTTP_CREATED);
    }

    public function showAction(ShowAuthorRequest $request): JsonResponse
    {
        $output = $this->authorService->getAuthorById($request->id);
        return response()->json($output, JsonResponse::HTTP_OK);
    }

    public function updateAction(UpdateAuthorRequest $request): JsonResponse
    {
        $output = $this->authorService->update(
            $request->id, 
            $request->input('name'), 
            $request->input('birth_date')
        );
        return response()->json($output, JsonResponse::HTTP_OK);
    }

    public function destroyAction(DestroyAuthorRequest $request): JsonResponse
    {
        $this->authorService->delete($request->id);
        return response()->json(status: JsonResponse::HTTP_NO_CONTENT);
    }
}
