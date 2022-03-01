<?php

namespace macropage\LaravelJsonSettings\Facades;

use Illuminate\Support\Facades\Facade;
use macropage\LaravelJsonSettings\SettingsRepository;

/**
 * @method static mixed get(string $key, mixed $default = null, ?string $namespace = null)
 * @method static static set(string $key, mixed $value, bool $save = true, ?string $namespace = null)
 * @method static bool has(string $key, ?string $namespace = null)
 * @method static static reload(?string $namespace)
 * @method static static save(int $flags, ?string $namespace = null)
 *
 * @see \macropage\LaravelJsonSettings\SettingsRepository
 */
class Settings extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return SettingsRepository::class;
    }
}
