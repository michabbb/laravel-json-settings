<?php
/** @noinspection PhpUnhandledExceptionInspection */

/** @noinspection StaticClosureCanBeUsedInspection */

use Illuminate\Support\Facades\File;
use function PHPUnit\Framework\assertDirectoryExists;
use function PHPUnit\Framework\assertFileExists;

use macropage\LaravelJsonSettings\SettingsRepository;

it('can be resolved from the container', function () {
    expect(app(SettingsRepository::class))->toBeInstanceOf(SettingsRepository::class);
});

it('creates an empty json file and directory if one does not exist', function () {
    File::delete(storage_path('settings.json'));
    File::deleteDirectory(storage_path('settings_json'));

    app(SettingsRepository::class);

    assertFileExists(storage_path('settings.json'));
    assertDirectoryExists(storage_path('settings_json'));
});

it('can read settings', function () {
    File::put(
        storage_path('settings.json'), json_encode([
                                                       'foo' => 'bar',
                                                   ], JSON_THROW_ON_ERROR));

    expect(
        app(SettingsRepository::class)
            ->get('foo'))
        ->toEqual('bar');
});

it('can write settings', function () {
    app(SettingsRepository::class)
        ->set('name', 'foo');

    expect(File::get(storage_path('settings.json')))
        ->toContain('"name": "foo"');
});

it('can write and read settings with namespace', function () {
    app(SettingsRepository::class)
        ->set('namespace_key1', 'namespace_value1', true, 'namespace1');

    app(SettingsRepository::class)
        ->set('namespace_key2', 'namespace_value2', true, 'namespace1');

    expect(File::exists(storage_path('settings_json') . '/namespace1.json'))->toBeTrue();

    app(SettingsRepository::class)
        ->set('namespace_key1', 'namespace_value1', true, 'namespace2');

    expect(File::exists(storage_path('settings_json') . '/namespace2.json'))->toBeTrue();

    expect(
        app(SettingsRepository::class)
            ->get('namespace_key1', 'namespace1'))
        ->toEqual('namespace_value1');

    expect(
        app(SettingsRepository::class)
            ->get('namespace_key2', 'namespace1'))
        ->toEqual('namespace_value2');

    expect(
        app(SettingsRepository::class)
            ->get('namespace_key1', 'namespace2'))
        ->toEqual('namespace_value1');
});

