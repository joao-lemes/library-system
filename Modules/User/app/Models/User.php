<?php

namespace Modules\User\Models;

use App\Services\Cryptography\JsonWebToken;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use JsonSerializable;
use Modules\Loan\Models\Loan;
use Modules\User\Database\Factories\UserFactory;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Model implements Authenticatable, JWTSubject, JsonSerializable
{
    use HasFactory, Notifiable;

    /** @var array<string> */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /** @var array<string> */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getAuthIdentifierName()
    {
        return 'id';
    }

    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    public function getAuthPasswordName()
    {
        return 'password';
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function getRememberToken()
    {
        return $this->remember_token;
    }

    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }

    public function getRememberTokenName()
    {
        return 'remember_token';
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public static function newFactory()
    {
        return UserFactory::new();
    }
    
    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }

    /** @return array<mixed> */
    public function jsonSerialize(): array
    {
        $isCurrentUser = Auth::check() && Auth::id() === $this->id;

        $data = [
            'id' => JsonWebToken::encode($this->id),
            'name' => $this->name,
            'created_at' => $this->created_at ? $this->created_at->format('d/m/Y H:i:s') : null, 
            'updated_at' => $this->updated_at ? $this->updated_at->format('d/m/Y H:i:s') : null, 
            'deleted_at' => $this->deleted_at ? $this->deleted_at->format('d/m/Y H:i:s') : null, 
        ];

        if ($isCurrentUser) {
            $additionalData = [
                'email' => $this->email,
            ];
    
            return array_merge($data, $additionalData);
        }

        return $data;
    }
}
