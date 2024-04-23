<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class StrMaster
{
    static public function htmlTagClear($content, $limit = 100)
    {
        $content = str_replace("<br>", "\n", $content);
        $content = Str::markdown($content);
        $content = str_replace("&quot;", "", $content);

        return Str::limit(strip_tags($content), $limit);
    }

    /**
     * @param $content
     * @return string
     */
    static public function applyHtml($content): string
    {
        $content = str_replace("<br>", "\n", $content);
        $content = Str::markdown($content);
        $content = str_replace("<a ", '<a target="blank" ', $content);

        return $content;
    }
}
