<?php

namespace App\Services\Proxy;

use App\Settings\SettingGeneral;

class Proxy
{

    /**
     * @return string
     */
    static public function getProxyString()
    {
        return '';
    }

    /**
     * @param $curlOptions
     * @return bool|string
     */
    static public function curl($curlOptions)
    {
        $curl = curl_init();

        $settings = new SettingGeneral();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.aiproxy.store/api/v1/curl',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            //CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($curlOptions),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer '.$settings->api_key_aisearch
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return $response;
    }

}
