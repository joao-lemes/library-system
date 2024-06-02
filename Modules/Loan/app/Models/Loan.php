<?php

namespace Modules\Loan\Models;

use App\Services\Cryptography\JsonWebToken;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use JsonSerializable;
use Modules\Book\Models\Book;
use Modules\User\Models\User;

class Loan extends Model implements JsonSerializable
{
    /** @var array<string> $fillable */
    protected $fillable = [
        'user_id', 
        'book_id', 
        'loan_date',
        'return_date'
    ];

    /** @var array<string> $casts */
    protected $casts = [
        'loan_date' => 'date:Y-m-d',
        'return_date' => 'date:Y-m-d',
    ];

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    /** @return array<mixed> */
    public function jsonSerialize(): array
    {
        $id = JsonWebToken::encode($this->id);
        return [
            'id' => $id,
            'loan_date' => $this->loan_date ? $this->loan_date->format('d/m/Y') : null,
            'return_date' => $this->return_date ? $this->return_date->format('d/m/Y') : null,
            'book' => $this->book()->first()->jsonSerialize(),
            'user' => $this->user()->first()->jsonSerialize(),
            'created_at' => $this->created_at ? $this->created_at->format('d/m/Y H:i:s') : null, 
            'updated_at' => $this->updated_at ? $this->updated_at->format('d/m/Y H:i:s') : null, 
            'deleted_at' => $this->deleted_at ? $this->deleted_at->format('d/m/Y H:i:s') : null,
            'links' => [
                [
                    'rel' => 'self',
                    'method' => 'GET',
                    'href' => route('api.loan.show', $id),
                ],
                [
                    'rel' => 'list',
                    'method' => 'GET',
                    'href' => route('api.loan.list'),
                ],
                [
                    'rel' => 'update',
                    'method' => 'PUT',
                    'href' => route('api.loan.return', $id),
                ]
            ],
        ];
    }
}
