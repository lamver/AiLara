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

    /**
     * @param string $string
     * @param string|array $entries
     * @return bool
     */
    static public function checkStrInString(string $string, string|array $entries): bool
    {
        if (is_array($entries)) {
            foreach ($entries as $word) {
                if (empty($word)) {
                    continue;
                }
                if (stripos($string, $word) !== false) {
                    return true;
                }
            }
        } else {
            if (empty($word)) {
                return false;
            }
            if (stripos($string, $entries) !== false) {
                return true;
            }
        }

        return false;
    }
}
