<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class Data extends Settings
{
    const TYPE_STRING = 'string';
    const TYPE_TEXT = 'text';
    const TYPE_INT = 'integer';
    const TYPE_BOOLEAN = 'boolean';

    public static function group(): string
    {
        // TODO: Implement group() method.
    }
}
