<?php

namespace MBLSolutions\LinkModuleLaravel;

class LinkDecryptionService
{
    public function __construct(
        private string $cipher,
        private string $key,
        private string $iv,
    ) {
    }

    public function decrypt(string $value): string
    {
        $result = @openssl_decrypt($value, $this->cipher, base64_decode($this->key), 0, base64_decode($this->iv));

        if ($result === false) {
            throw new \RuntimeException('Failed to decrypt value: openssl_decrypt returned false.');
        }

        return $result;
    }
}
