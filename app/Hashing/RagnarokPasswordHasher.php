<?php

namespace App\Hashing;

use App\Models\User;
use Illuminate\Hashing\BcryptHasher;

class RagnarokPasswordHasher extends BcryptHasher
{
    public function check($value, $hashedValue, array $options = []): bool
    {
        if ($hashedValue === $value || $hashedValue === md5($value)) {
            return true;
        }

        $expected = match (ServerHashesEnum::tryFrom(config('ragnarok.password_encryption')) ?? ServerHashesEnum::PLAINTEXT) {
            ServerHashesEnum::PLAINTEXT => $value,
            ServerHashesEnum::MD5 => md5($value),
        };

        return hash_equals($hashedValue, $expected);
    }
}
