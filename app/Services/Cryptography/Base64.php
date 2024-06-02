<?php

declare(strict_types=1);

namespace App\Services\Cryptography;

use App\Exceptions\BadRequestException;

abstract class Base64
{
    public static function encode(string $payload): string
    {
        $base64 = base64_encode($payload);
        $base64Url = strtr($base64, '+/', '-_');
        return rtrim($base64Url, '=');
    }

    public static function decode(string $base64Url): string
    {
        $base64 = strtr($base64Url, '-_', '+/');
        $decoded = base64_decode($base64);
        if ($decoded === false) {
            throw new BadRequestException(trans('exception.error_decoding_base64'));
        }
        return $decoded;
    }
}
