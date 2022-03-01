# Store your Laravel application settings in JSON files.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/macropage/laravel-json-settings.svg?style=flat-square)](https://packagist.org/packages/macropage/laravel-json-settings)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/michabbb/laravel-json-settings/run-tests?label=tests)](https://github.com/michabbb/laravel-json-settings/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/michabbb/laravel-json-settings/Check%20&%20fix%20styling?label=code%20style)](https://github.com/michabbb/laravel-json-settings/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/michabbb/laravel-json-settings.svg?style=flat-square)](https://packagist.org/packages/macropage/laravel-json-settings)

This is a fork of https://github.com/ryangjchandler/laravel-json-settings  
The main change is: I added namespaces that get saved into individual files.  
I still don´t know if that is good idea, so let´s find out ;)

This package provides a simple `SettingsRepository` class that can be used to store your application's settings in a single JSON file.

## Installation

You can install the package via composer:

```bash
composer require macropage/laravel-json-settings
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="json-settings-config"
```

## Usage

You can resolve an instance of `macropage\LaravelJsonSettings\SettingsRepository` from the container by type-hinting it in any DI-supported method, e.g. a controller method.

```php
class IndexController
{
    public function __invoke(SettingsRepository $settings)
    {
        return view('index', [
            'title' => $settings->get('index.title'),
        ]);
    }
}
```

The `SettingsRepository` class contains the following methods:

* `get(string $key, mixed $default = null)` - retrieve the value of a setting by providing the key (dot-notation supported).
* `set(string $key, mixed $value, bool $save = true)` - set the value of a setting and toggle auto-save.
* `has(string $key)` - determine if a setting exists.
* `save()` - manually save your settings back to disk.
* `reload()` - clear the cache and reload the settings from disk.

If you prefer to use facades, you can interact with the `macropage\LaravelJsonSettings\Facades\Settings` facade directly too.

## Testing

```bash
composer test
```

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Credits

- [Michael Bladowski](https://github.com/michabbb)
- [Ryan Chandler](https://github.com/ryangjchandler)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
