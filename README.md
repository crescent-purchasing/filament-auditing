# Basic Management of O3 IT's Laravel Auditing library

[![Latest Version on Packagist](https://img.shields.io/packagist/v/crescent-purchasing/filament-auditing.svg?style=flat-square)](https://packagist.org/packages/crescent-purchasing/filament-auditing)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/crescent-purchasing/filament-auditing/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/crescent-purchasing/filament-auditing/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/crescent-purchasing/filament-auditing/fix-php-code-styling.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/crescent-purchasing/filament-auditing/actions?query=workflow%3A"Fix+PHP+code+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/crescent-purchasing/filament-auditing.svg?style=flat-square)](https://packagist.org/packages/crescent-purchasing/filament-auditing)



This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require crescent-purchasing/filament-auditing
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="filament-auditing-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="filament-auditing-config"
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="filament-auditing-views"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

```php
$filamentAuditing = new CrescentPurchasing\FilamentAuditing();
echo $filamentAuditing->echoPhrase('Hello, CrescentPurchasing!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Crescent Purchasing .Ltd](https://github.com/crescent-purchasing)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
