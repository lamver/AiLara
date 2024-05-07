<?php

namespace Database\Seeders;

use App\Services\Modules\Module;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModuleCommonConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('modules_common_configs')->insert([
            'const_module_name' => Module::MODULE_AI_FORM,
            'use_on_front' => true,
            'prefix_uri' => 'ai-form',
        ]);

        DB::table('modules_common_configs')->insert(
            [
                'const_module_name' => Module::MODULE_BLOG,
                'use_on_front' => true,
                'prefix_uri' => 'blog',
            ]
        );
    }
}
