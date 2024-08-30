<?php

declare(strict_types=1);

namespace Phelium\Component;

class Encryptor
{
    private string $key;
    private string $cipher;
    private string $hmacAlgo;
    private int $options = 0;

    public function __construct()
    {
        $this->setCipher('aes-256-cbc');
        $this->setHmacAlgo('sha256');
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function setKey(string $key): self
    {
        $this->key = hash('sha256', $key, true);

        return $this;
    }

    public function getCipher(): string
    {
        return $this->cipher;
    }

    public function setCipher(string $cipher): self
    {
        $cipher = strtolower($cipher);
        
        if (!in_array($cipher, openssl_get_cipher_methods())) {
            throw new \InvalidArgumentException('Invalid cipher method.');
        }

        $this->cipher = $cipher;

        return $this;
    }

    public function getOptions(): int
    {
        return $this->options;
    }

    public function setOptions(int $options)
    {
        $availableOptions = [OPENSSL_RAW_DATA, OPENSSL_ZERO_PADDING];
        if (!in_array($options, $availableOptions)) {
            throw new \InvalidArgumentException('Invalid option.');
        }

        $this->options = $options;

        return $this;
    }

    public function getHmacAlgo(): string
    {
        return $this->hmacAlgo;
    }

    public function setHmacAlgo(string $hmacAlgo): self
    {
        $this->hmacAlgo = $hmacAlgo;

        return $this;
    }

    public function encrypt(string $data): string
    {
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($this->getCipher()));
        $encrypted = openssl_encrypt($data, $this->getCipher(), $this->getKey(), $this->getOptions(), $iv);
        $hmac = hash_hmac($this->getHmacAlgo(), $iv . $encrypted, $this->getKey(), true);

        return base64_encode($iv . $hmac . $encrypted);
    }

    public function decrypt(string $data): string
    {
        $data = base64_decode($data);
        $ivLength = openssl_cipher_iv_length($this->getCipher());
        $hmacLength = strlen(hash($this->getHmacAlgo(), '', true));

        $iv = substr($data, 0, $ivLength);
        $hmac = substr($data, $ivLength, $hmacLength);
        $encrypted = substr($data, $ivLength + $hmacLength);

        $calculatedHmac = hash_hmac($this->getHmacAlgo(), $iv . $encrypted, $this->getKey(), true);

        if (!hash_equals($hmac, $calculatedHmac)) {
            throw new \RuntimeException('HMAC verification failed.');
        }

        return openssl_decrypt($encrypted, $this->getCipher(), $this->getKey(), $this->getOptions(), $iv);
    }

    public static function generateKey(): string
    {
        return bin2hex(openssl_random_pseudo_bytes(32));
    }
}
