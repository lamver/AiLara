<?php


namespace App\Models;


use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;

class AiLaraConfig
{
    const TYPE_STRING = 'string';
    const TYPE_TEXT = 'text';
    const TYPE_INT = 'int';

    const CONFIG_PATTERN = [
        'value' => '',
        'type'  => self::TYPE_STRING,
        'label' => 'Any value',
        'description' => '',
    ];

    public function getAll() : array
    {
        return $this->fillValues();
    }

    /**
     * @return array
     */
    private function fillValues() : array
    {
        Artisan::call('config:clear');
        $currentConfigValues = Config::get('ailara');
        $configFill = [];

        foreach ($currentConfigValues as $configKey => $configValue) {
            $configFill[$configKey] = self::CONFIG_PATTERN;
            $configFill[$configKey]['value'] = $configValue;
            $configFill[$configKey]['label'] = $configKey;
        }

        return $configFill;
    }

    /**
     * @param array $data
     * @return void
     */
    public function save(array $data)
    {
        if (isset($data['_token'])) {
            unset($data['_token']);
        }

        file_put_contents(getcwd().'/../config/ailara.php', '<?php return ' . var_export($data, true) . ';');

        Artisan::call('cache:clear');
        Artisan::call('config:cache');
    }

}
