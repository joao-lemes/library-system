<?php

declare(strict_types=1);

namespace App\Services\Cryptography;

use App\Exceptions\BadRequestException;

abstract class JsonWebToken
{
    /** @var array<string> HEADER */
    private const HEADER = [
        'alg' => 'HS256',
        'typ' => 'JWT'
    ];

    public static function encode(mixed $payload): string
    {
        $base64UrlHeader = Base64::encode(json_encode(self::HEADER));
        $base64UrlPayload = Base64::encode(json_encode($payload));

        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, self::getSecret(), true);
        $base64UrlSignature = Base64::encode($signature);

        return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    }

    public static function decode(string $jwt): mixed
    {
        list($base64UrlHeader, $base64UrlPayload, $base64UrlSignature) = explode('.', $jwt);

        $payload = json_decode(Base64::decode($base64UrlPayload), true);

        $signature = Base64::decode($base64UrlSignature);

        $expectedSignature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, self::getSecret(), true);

        if ($signature !== $expectedSignature) {
            throw new BadRequestException(trans('exception.invalid_signature'));
        }

        return $payload;
    }

    private static function getSecret() : string
    {
        return config('jwt.secret');
    }
}
