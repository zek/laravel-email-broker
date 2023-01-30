# Simple Laravel Email Broker.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/zek/laravel-email-broker.svg?style=flat-square)](https://packagist.org/packages/zek/laravel-email-broker)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/zek/laravel-email-broker/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/zek/laravel-email-broker/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/zek/laravel-email-broker/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/zek/laravel-email-broker/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/zek/laravel-email-broker.svg?style=flat-square)](https://packagist.org/packages/zek/laravel-email-broker)

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require zek/laravel-email-broker
```

You can publish and run the migrations with:

```bash
php artisan migrate
```

Update contents of auth.php:

```php
return [
    'defaults' => [
        'guard' => 'web',
        'emails' => 'users', // <-- Add this line 
    ],
    // ...
    // Add following lines below
    'emails' => [
        'users' => [
            'model' => App\Models\User::class,
            'table' => 'email_changes',
            'expire' => 60, // 60 minutes
            'length' => 6, // Generated token length
        ]
    ],
];
```

## Usage

```php
$EmailBroker = new Zek\EmailBroker();
echo $EmailBroker->echoPhrase('Hello, Zek!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Talha Zekeriya Durmuş](https://github.com/zek)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
