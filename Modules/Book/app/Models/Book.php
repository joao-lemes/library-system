<?php

namespace Modules\Book\Models;

use App\Services\Cryptography\JsonWebToken;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use JsonSerializable;
use Modules\Author\Models\Author;

class Book extends Model implements JsonSerializable
{
    use SoftDeletes;

    /** @var array<string> $fillable */
    protected $fillable = [
        'title', 'year_of_publication'
    ];

    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class, 'author_book');
    }
    
    /** @return array<mixed> */
    public function jsonSerialize(): array
    {
        $id = JsonWebToken::encode($this->id);
        return [
            'id' => $id,
            'title' => $this->title,
            'year_of_publication' => $this->year_of_publication,
            'authors' => $this->authors()->get(),
            'created_at' => $this->created_at ? $this->created_at->format('d/m/Y H:i:s') : null, 
            'updated_at' => $this->updated_at ? $this->updated_at->format('d/m/Y H:i:s') : null, 
            'deleted_at' => $this->deleted_at ? $this->deleted_at->format('d/m/Y H:i:s') : null,
            'links' => [
                [
                    'rel' => 'self',
                    'method' => 'GET',
                    'href' => route('api.book.show', $id),
                ],
                [
                    'rel' => 'list',
                    'method' => 'GET',
                    'href' => route('api.book.list'),
                ],
                [
                    'rel' => 'update',
                    'method' => 'PUT',
                    'href' => route('api.book.update', $id),
                ],
                [
                    'rel' => 'delete',
                    'method' => 'DELETE',
                    'href' => route('api.book.destroy', $id),
                ],
            ],
        ];
    }
}
