<?php


namespace App\Models;


use Illuminate\Support\Facades\Config;

class AiLaraConfig
{
    const TYPE_STRING = 'string';
    const TYPE_TEXT = 'text';
    const TYPE_INT = 'int';

    private array $config = [
            'appName' => [
                'value' => '',
                'type'  => self::TYPE_STRING,
                'label' => 'Name of app',
                'description' => '',
            ],
            'logoPath' => [
                'value' => '',
                'type'  => self::TYPE_STRING,
                'label' => 'Path to logo',
                'description' => '',
            ],
            'logoTitle' => [
                'value' => '',
                'type'  => self::TYPE_STRING,
                'label' => 'Title to logo',
                'description' => '',
            ],
            'counterExternalCode' => [
                'value' => '',
                'type'  => self::TYPE_TEXT,
                'label' => 'Counter code yandex metrika or google analytics',
                'description' => '',
            ],
            'test' => [
                'value' => 0,
                'type'  => self::TYPE_INT,
                'label' => 'test',
                'description' => '',
            ],
            'api_key_aisearch' => [
                'value' => '',
                'type'  => self::TYPE_STRING,
                'label' => 'Api key from aisearch',
                'description' => '',
            ],
            'api_host' => [
                'value' => '',
                'type'  => self::TYPE_STRING,
                'label' => 'Api host from aisearch',
                'description' => '',
            ],
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
        $currentConfigValues = Config::get('ailara');

        foreach ($this->config as $key => $value) {
            if (isset($currentConfigValues[$key])) {
                $this->config[$key]['value'] = $currentConfigValues[$key];
            }
        }

        return $this->config;
    }

    public function save(array $data)
    {
        $config = [];

        foreach ($data as $key => $value) {
            if (isset($this->config[$key])) {
                $config[$key] = $value;
            }
        }

        file_put_contents(getcwd().'/../config/ailara.php', '<?php return ' . var_export($config, true) . ';');
    }

}
