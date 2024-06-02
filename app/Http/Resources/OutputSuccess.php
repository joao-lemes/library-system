<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

abstract class OutputSuccess extends JsonResource
{
    public function toArray($request)
    {
        return [
            'success' => true,
            'error' => false,
            'response' => $this->getMessage()
        ];
    }

    /** @return array<string,bool> */
    protected function formatPagination(): array
    {
        $data = [];
        foreach ($this->resource->items() as $value) {
            $data[] = $value?->jsonSerialize();
        }

        return [
            "current_page" => $this->resource->currentPage(),
            "data" => $data,
            "first_page_url" => $this->resource->path() . '?page=1',
            "last_page" => $this->resource->lastPage(),
            "last_page_url" => $this->resource->path() . '?page=' . $this->resource->lastPage(),
            "links" => $this->paginationLinks(),
            "next_page_url" => $this->resource->nextPageUrl(),
            "path" => $this->resource->path(),
            "per_page" => $this->resource->perPage(),
            "prev_page_url" => $this->resource->previousPageUrl(),
            "total" => $this->resource->total()
        ];
    }

    /** @return array<string,bool> */
    protected function paginationLinks(): array
    {
        $links = [];

        $links[] = [
            'url' => $this->resource->previousPageUrl(),
            'label' => '&laquo; Anterior',
            'active' => $this->resource->currentPage() > 1,
        ];

        for ($page = 1; $page <= $this->resource->lastPage(); $page++) {
            $links[] = [
                'url' => $this->resource->url($page),
                'label' => (string) $page,
                'active' => $this->resource->currentPage() === $page,
            ];
        }

        $links[] = [
            'url' => $this->resource->nextPageUrl(),
            'label' => 'Próximo &raquo;',
            'active' => $this->resource->currentPage() < $this->resource->lastPage(),
        ];

        return $links;
    }

    abstract protected function getMessage();
}
