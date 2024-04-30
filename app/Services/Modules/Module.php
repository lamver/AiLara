<?php


namespace App\Services\Modules;


class Module
{
    const MODULE_BLOG = 'MODULE_BLOG';
    const MODULE_BLOG_NAME = 'Blog';
    const MODULE_AI_FORM = 'MODULE_AI_FORM';
    const MODULE_AI_FORM_NAME = 'Ai forms';

    const MODULE_CONFIG = [
        self::MODULE_BLOG => [
            'name' => self::MODULE_BLOG_NAME,
            'controller' => \App\Http\Controllers\Modules\Blog\PostsController::class,
            'action' => 'index',
            'route_prefix' => '/',
            'use_on_front' => true,
        ],
        self::MODULE_AI_FORM => [
            'name' => self::MODULE_AI_FORM_NAME,
            'controller' => \App\Http\Controllers\Modules\Task\TaskController::class,
            'action' => 'index',
            'route_prefix' => '/task1',
            'use_on_front' => true,
        ]
    ];

    /**
     * @return array
     */
    static public function getAllModulesUseOnFront() : array
    {
        $moduleFront = [];

        foreach (self::MODULE_CONFIG as $moduleKey => $moduleConfig) {
            if (isset($moduleConfig['use_on_front']) && $moduleConfig['use_on_front']) {
                $moduleFront[$moduleKey] = $moduleConfig;
            }
        }

        return $moduleFront;
    }

    /**
     * @param string $moduleConst
     *
     * @return mixed|string
     */
    static public function getModuleName(string $moduleConst) : mixed
    {
        if (!isset(self::MODULE_CONFIG[$moduleConst])) {
            return 'unknown';
        }

        if (!isset(self::MODULE_CONFIG[$moduleConst]['name'])) {
            return 'unknown';
        }

        return self::MODULE_CONFIG[$moduleConst]['name'];
    }

}
