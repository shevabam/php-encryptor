<?php

declare(strict_types=1);

namespace Phelium\Component\Encryptor\Tests;

use Phelium\Component\Encryptor;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
class EncryptorTest extends TestCase
{
    const KEY = 'abc123';

    public function testEncryptDecrypt(): void
    {
        $encryptor = new Encryptor();
        $encryptor->setKey(self::KEY);

        $originalString = 'my string';
        $encryptedString = $encryptor->encrypt($originalString);
        $decryptedString = $encryptor->decrypt($encryptedString);

        self::assertNotEquals($originalString, $encryptedString, 'The encrypted string should not be the same as the original.');
        self::assertEquals($originalString, $decryptedString, 'The decrypted string should match the original.');
    }

    public function testCustomCipher(): void
    {
        $encryptor = new Encryptor();
        $encryptor->setKey(self::KEY);
        $encryptor->setCipher('aes-128-cbc');

        $originalString = 'my string';
        $encryptedString = $encryptor->encrypt($originalString);
        $decryptedString = $encryptor->decrypt($encryptedString);

        self::assertNotEquals($originalString, $encryptedString, 'The encrypted string should not be the same as the original.');
        self::assertEquals($originalString, $decryptedString, 'The decrypted string should match the original.');
    }

    public function testInvalidCipher(): void
    {
        $encryptor = new Encryptor();
        $encryptor->setKey(self::KEY);

        self::expectException(\InvalidArgumentException::class);

        $encryptor->setCipher('UNKNOWN_CIPHER');
    }

    public function testCustomOption(): void
    {
        $encryptor = new Encryptor('aes-128-cbc');
        $encryptor->setKey(self::KEY);
        $encryptor->setOptions(OPENSSL_RAW_DATA);

        $originalString = 'my string';
        $encryptedString = $encryptor->encrypt($originalString);
        $decryptedString = $encryptor->decrypt($encryptedString);

        self::assertNotEquals($originalString, $encryptedString, 'The encrypted string should not be the same as the original.');
        self::assertEquals($originalString, $decryptedString, 'The decrypted string should match the original.');
    }

    public function testInvalidOption(): void
    {
        $encryptor = new Encryptor();
        $encryptor->setKey(self::KEY);

        self::expectException(\InvalidArgumentException::class);

        $encryptor->setOptions(999);
    }

    public function testGenerateKey(): void
    {
        $key = Encryptor::generateKey();
        self::assertEquals(64, strlen($key), 'Generated key should be 64 characters long (256 bits in hex).');
    }

    public function testDecryptWithInvalidHmac(): void
    {
        $encryptor = new Encryptor();
        $encryptor->setKey(self::KEY);

        $originalString = 'my string';
        $encryptedString = $encryptor->encrypt($originalString);

        $invalidEncryptedString = substr_replace($encryptedString, 'A', -1);

        self::expectException(\RuntimeException::class);
        $encryptor->decrypt($invalidEncryptedString);
    }
}
