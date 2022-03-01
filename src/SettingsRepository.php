<?php

namespace macropage\LaravelJsonSettings;

use Illuminate\Cache\TaggedCache;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use JsonException;

class SettingsRepository
{
    protected array $settings            = [];
    protected array $settings_namespaced = [];

    /**
     * @throws JsonException
     */
    public function __construct(
        protected string $path,
        protected string $namespace_path,
    ) {
        if (!file_exists($this->path)) {
            File::put($this->path, json_encode([], JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_FORCE_OBJECT));
        }
        if (!file_exists($this->namespace_path)) {
            File::makeDirectory($this->namespace_path);
        }

        $this->load();
    }

    public function get(string $key, ?string $namespace = null, mixed $default = null): mixed
    {
        if ($namespace) {
            return Arr::get($this->settings['__'.$namespace], $key, $default);
        }
        return Arr::get($this->settings, $key, $default);
    }

    /**
     * @throws JsonException
     */
    public function set(string $key, mixed $value, bool $save = true, ?string $namespace = null,?int $flags = 0): static
    {
        if ($namespace) {
            Arr::set($this->settings['__'.$namespace], $key, $value);
        } else {
            Arr::set($this->settings, $key, $value);
        }

        if ($save) {
            $this->save($namespace,$flags);
        }

        return $this;
    }

    public function has(string|array $keys,?string $namespace=null): bool
    {
        if ($namespace) {
            return Arr::has($this->settings['__'.$namespace], $keys);
        }

        return Arr::has($this->settings, $keys);
    }

    /**
     * @throws JsonException
     */
    public function save(?string $namespace=null, int $flags = 0): static
    {
        if ($namespace) {
            File::put($this->namespace_path.'/'.$namespace.'.json', json_encode($this->settings['__'.$namespace], JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | $flags));
        } else {
            File::put($this->path, json_encode($this->settings, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | $flags));
        }

        $this->forget($namespace);

        return $this;
    }

    public function reload(?string $namespace = null): static
    {
        $this->forget($namespace);
        $this->load($namespace);

        return $this;
    }

    protected function forget(?string $namespace=null): void
    {
        if ($namespace) {
            $this->getCacheStore()->forget($this->getCacheKeyNamespace().$namespace);
        } else {
            $this->getCacheStore()->forget($this->getCacheKey());
        }
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getPathNamespace(): string
    {
        return $this->namespace_path;
    }

    protected function getCacheKey(): string
    {
        return config('json-settings.cache.key');
    }

    protected function getCacheKeyNamespace(): string
    {
        return config('json-settings.cache.key_prefix_namespace');
    }

    protected function getCacheTag(): ?string
    {
        return config('json-settings.cache.tag');
    }

    protected function getCacheStore(): TaggedCache|Repository
    {
        return $this->getCacheTag() ? Cache::tags($this->getCacheTag()) : Cache::store();
    }

    protected function load(?string $namespace=null): void
    {
        if ($namespace) {
            if (File::exists($this->namespace_path.'/'.$namespace.'.json')) {
                $this->settings['__'.$namespace] = $this->getCacheStore()
                                       ->rememberForever($this->getCacheKeyNamespace().$namespace, function () use ($namespace) {
                                           return json_decode(File::get($this->namespace_path.'/'.$namespace.'.json'), true, 512, JSON_THROW_ON_ERROR);
                                       });
            }
        } else {
            $this->settings = $this->getCacheStore()
                                   ->rememberForever($this->getCacheKey(), function () {
                                       return json_decode(File::get($this->path), true, 512, JSON_THROW_ON_ERROR);
                                   });
            foreach (File::allFiles($this->namespace_path) as $file) {
                if (pathinfo($file, PATHINFO_EXTENSION) === 'json') {
                    $this->settings['__' . basename($file)] = $this->getCacheStore()
                                                                   ->rememberForever($this->getCacheKeyNamespace() . basename($file), function () use ($file) {
                                                                       return json_decode(File::get($file), true, 512, JSON_THROW_ON_ERROR);
                                                                   });
                }
            }
        }
    }
}
