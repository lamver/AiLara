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
    static public function resizeImgFromCdn(string $url, int $h = 512, int $w = 512): string
    {
        $url = str_replace('width=1024', 'width=' . $w, $url);
        return str_replace('height=1024', 'height=' . $h, $url);
    }
}
