<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ModulesCommonConfig extends Model
{
    use HasFactory;

    const CACHE_MODULES_MAIN_CONF = 'CACHE_MODULES_MAIN_CONF';

    protected $fillable = [
        'prefix_uri', 'use_on_front'
    ];

    static public function store($data = []): bool|string
    {
        $configs = self::query()->select(['id', 'const_module_name', 'prefix_uri', 'use_on_front'])->get();

        foreach ($configs as $config) {

            if (array_key_exists($config->const_module_name . '_prefix_uri', $data)) {
                $config->prefix_uri = $data[$config->const_module_name . '_prefix_uri'];

                if (is_null($config->prefix_uri)) {
                    $config->prefix_uri = '';
                }
            }

            if (array_key_exists($config->const_module_name . '_use_on_front', $data)) {
                $config->use_on_front = $data[$config->const_module_name . '_use_on_front'];
            } else {
                $config->use_on_front = false;
            }

            try {
                $config->save();
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        }

        self::loadModulesMainConfig(false);

        return true;
    }

    /**
     * @param bool $cache
     *
     * @return false|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|mixed
     */
    static public function loadModulesMainConfig(bool $cache = true) : mixed
    {
        if ($cache && Cache::has(self::CACHE_MODULES_MAIN_CONF)) {
            return Cache::get(self::CACHE_MODULES_MAIN_CONF);
        }

        try {
            $configs = self::query()->select(['id', 'const_module_name', 'prefix_uri', 'use_on_front'])->get();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return false;
        }

        Cache::set(self::CACHE_MODULES_MAIN_CONF, $configs);

        return $configs;
    }
}
