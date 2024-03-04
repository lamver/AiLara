<?php


namespace App\Services;

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
            throw new ('fefef');
        }
        $this->apiKey = $apiKey;
        $this->apiHost = $apiHost;

        return $this;
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
}
