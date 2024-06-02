<?php

namespace Modules\Author\Models;

use App\Services\Cryptography\JsonWebToken;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use JsonSerializable;

class Author extends Model implements JsonSerializable
{
    use SoftDeletes;

    /** @var array<string> $fillable */
    protected $fillable = [
        'name', 'birth_date'
    ];
    
    /** @return array<mixed> */
    public function jsonSerialize(): array
    {
        $id = JsonWebToken::encode($this->id);
        return [
            'id' => $id,
            'name' => $this->name,
            'birth_date' => $this->birth_date,
            'created_at' => $this->created_at ? $this->created_at->format('d/m/Y H:i:s') : null, 
            'updated_at' => $this->updated_at ? $this->updated_at->format('d/m/Y H:i:s') : null, 
            'deleted_at' => $this->deleted_at ? $this->deleted_at->format('d/m/Y H:i:s') : null,
            'links' => [
                [
                    'rel' => 'self',
                    'method' => 'GET',
                    'href' => route('api.author.show', $id),
                ],
                [
                    'rel' => 'list',
                    'method' => 'GET',
                    'href' => route('api.author.list'),
                ],
                [
                    'rel' => 'update',
                    'method' => 'PUT',
                    'href' => route('api.author.update', $id),
                ],
                [
                    'rel' => 'delete',
                    'method' => 'DELETE',
                    'href' => route('api.author.destroy', $id),
                ],
            ],
        ];
    }
}
