<?php


namespace App\Services;

use Illuminate\Support\Facades\Http;

/**
 * Class AiSearchApi
 *
 * @package App\Services
 */
class AiSearchApi
{
    private string $apiKey = '';
    private string $apiHost = '';

    /**
     * AiSearchApi constructor.
     *
     * @param string $apiKey
     * @param string $apiHost
     */
    public function __construct(string $apiKey, string $apiHost)
    {
        if (empty($apiKey)) {
            throw new ('$apiKey must be required');
        }

        if (empty($apiHost)) {
            throw new ('$apiHost must be required');
        }

        $this->apiKey = $apiKey;
        $this->apiHost = $apiHost;

        return $this;
    }

    public function taskCreate(string $prompt)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
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
            CURLOPT_POSTFIELDS => array('prompt' => $prompt),
        ));

        $response = curl_exec($curl);

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

    public function getAllTypesTask()
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
            $this->apiHost . "/api/services/ai-chats/result?id_task=" . $taskId
        )->json();
    }
}
