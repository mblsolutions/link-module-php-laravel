<?php

declare(strict_types=1);

namespace Tests\Unit;

use MBLSolutions\LinkModuleLaravel\LinkDecryptionService;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class LinkDecryptionServiceTest extends TestCase
{
    private string $key;
    private string $iv;

    protected function setUp(): void
    {
        parent::setUp();

        // chacha20 requires a 32-byte key and 16-byte IV
        $this->key = base64_encode(str_repeat('a', 32));
        $this->iv = base64_encode(str_repeat('b', 16));
    }

    #[Test]
    public function it_decrypts_a_value_encrypted_with_the_same_key_and_iv(): void
    {
        $rawKey = base64_decode($this->key);
        $rawIv = base64_decode($this->iv);
        $plaintext = 'my-short-code';
        $encrypted = openssl_encrypt($plaintext, 'chacha20', $rawKey, 0, $rawIv);

        $service = new LinkDecryptionService('chacha20', $this->key, $this->iv);

        $this->assertSame($plaintext, $service->decrypt($encrypted));
    }

    #[Test]
    public function it_throws_a_runtime_exception_when_decryption_fails(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Failed to decrypt value');

        // An unknown cipher causes openssl_decrypt to return false reliably
        $service = new LinkDecryptionService(
            'invalid-cipher-name',
            base64_encode(str_repeat('a', 32)),
            base64_encode(str_repeat('b', 16))
        );

        $service->decrypt('garbage-value');
    }
}
