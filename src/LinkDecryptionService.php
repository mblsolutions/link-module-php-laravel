<?php

namespace MBLSolutions\LinkModuleLaravel;

class LinkDecryptionService
{
    public function __construct(
        private string $cipher,
        private string $key,
        private string $iv,
    ) {}

    public function decrypt(string $value): string
    {
        return openssl_decrypt($value, $this->cipher, base64_decode($this->key), 0, base64_decode($this->iv));
    }
}
