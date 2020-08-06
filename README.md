# Blog from Bear App

[![Latest Version on Packagist](https://img.shields.io/packagist/v/azurinspire/bear-blogger.svg?style=flat-square)](https://packagist.org/packages/azurinspire/bear-blogger)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/azurinspire/bear-blogger/run-tests?label=tests)](https://github.com/azurinspire/bear-blogger/actions?query=workflow%3Arun-tests+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/azurinspire/bear-blogger.svg?style=flat-square)](https://packagist.org/packages/azurinspire/bear-blogger)


This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require azurinspire/bear-blogger
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --provider="AzurInspire\BearBlogger\BearBloggerServiceProvider" --tag="migrations"
php artisan migrate
```

You can publish the config file with:
```bash
php artisan vendor:publish --provider="AzurInspire\BearBlogger\BearBloggerServiceProvider" --tag="config"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

``` php
$bear-blogger = new AzurInspire\BearBlogger();
echo $bear-blogger->echoPhrase('Hello, AzurInspire!');
```

## Testing

``` bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email me@azurinspire.com instead of using the issue tracker.

## Credits

- [Kalle Pyörälä](https://github.com/azurinspire)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
