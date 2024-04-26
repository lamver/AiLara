<?php

namespace App\Helpers;

class ImageMaster
{
    /**
     * @param $url
     * @param int $h
     * @param int $w
     * @return array|string|string[]
     */
    static public function resizeImgFromCdn(null|string $url, int $h = 512, int $w = 512): string|null
    {
        if (is_null($url)) {
            return $url;
        }

        $url = str_replace('width=1024', 'width=' . $w, $url);
        return str_replace('height=1024', 'height=' . $h, $url);
    }

    static public function getRandomSprite()
    {
        $sprite = [
            'green',
            'light',
            'red',
        ];

        return '/images/sprite/' . $sprite[array_rand($sprite)] .'.png';
    }
}
