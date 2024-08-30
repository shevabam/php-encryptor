# Encryptor

**Encryptor** is a simple and effective PHP class for encrypting and decrypting strings using the `openssl_encrypt` and `openssl_decrypt` methods with HMAC verification for data integrity.

## Installation

You can install this library via [Composer](https://getcomposer.org/). Just run the following command in your project:

```bash
composer require phelium/encryptor
```

## Usage

### Initializing the Class

Start by creating an instance of the Encryptor class and setting an encryption key:

```php
use Phelium\Component\Encryptor;

$encryptor = new Encryptor();
$encryptor->setKey('my-super-key');
```

### Encrypting a String

Use the encrypt method to encrypt a string:

```php
$encryptedString = $encryptor->encrypt('my string');
echo $encryptedString;
```

### Decrypting a String

Use the decrypt method to decrypt a previously encrypted string:

```php
$decryptedString = $encryptor->decrypt($encryptedString);
echo $decryptedString; // Outputs "my string"
```

### Customizing the Encryption Algorithm

You can also customize the encryption algorithm used by the class:

```php
$encryptor->setCipherAlgorithm('aes-192-cbc');
```

Algorightms are available on [`openssl_get_cipher_methods`](https://www.php.net/manual/en/function.openssl-get-cipher-methods.php).

### Generating a Secure Key

If you need to generate a secure encryption key, use the following static method:

```php
$secureKey = Encryptor::generateKey();
echo $secureKey;
```

## Testing

This library comes with a PHPUnit test suite. You can run the tests with the following command:

```bash
composer test
```

## Code Coverage

You can also generate a code coverage report by running the following command:

```bash
composer test:coverage
```

The coverage report will be generated in the coverage-report directory as HTML files. You can open index.html in a browser to view the report.

## Contributing

Contributions are welcome! Feel free to submit issues and pull requests to improve this library.
