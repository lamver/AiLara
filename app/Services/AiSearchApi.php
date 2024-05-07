<?php


namespace App\Services;

use App\Settings\SettingGeneral;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Class AiSearchApi
 *
 * @package App\Services
 */
class AiSearchApi
{
    private null|string $apiKey = '';
    private null|string $apiHost = '';

    /**
     * AiSearchApi constructor.
     *
     * @param string|null $apiKey
     * @param string|null $apiHost
     */
    public function __construct(string $apiKey = null, string $apiHost = null)
    {
        $this->apiKey = $apiKey ?? SettingGeneral::value('api_key_aisearch');
        $this->apiHost = $apiHost ?? SettingGeneral::value('api_host');

        return $this;
    }

    /**
     * @param array $param
     *
     * @return mixed
     */
    public function taskCreate(array $param = [])
    {
        $curl = curl_init();

        $curlParam = [
            CURLOPT_URL => 'https://'.$this->apiHost.'/api/v1/task/create',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$this->apiKey
            ),
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_POSTFIELDS => $param,
        ];

        curl_setopt_array($curl, $curlParam);

        if (($response = curl_exec($curl)) === false) {
            Log::error(curl_error($curl));
        }

        if (curl_errno($curl)) {
             Log::error(__METHOD__ .'----'.curl_error($curl));;
        }

        curl_close($curl);

        return json_decode($response, true);
    }

    /**
     * @return mixed
     */
    public function getUserData() : mixed
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://'.$this->apiHost.'/api/v1/user/data',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$this->apiKey
            ),
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        ));

        $response = curl_exec($curl);

        if(curl_exec($curl) === false) {
            echo 'Ошибка curl: ' . curl_error($curl);
        }

        curl_close($curl);

        return json_decode($response, true);
    }

    /**
     * @return mixed
     */
    public function getAllTypesTask() : mixed
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://'.$this->apiHost.'/api/v1/task/get-all-types',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$this->apiKey
            ),
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        ));

        $response = curl_exec($curl);

        if(curl_exec($curl) === false) {
            echo 'Ошибка curl: ' . curl_error($curl);
        }

        curl_close($curl);

        return json_decode($response, true);
    }


    /**
     * @param int $taskId
     *
     * @return array|null
     */
    public function getTaskByTaskId(int $taskId): array|null
    {
        return Http::withToken($this->apiKey)->get(
            'https://' . $this->apiHost . "/api/services/ai-chats/result?id_task=" . $taskId
        )->json();
    }
}
