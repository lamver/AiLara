<?php


namespace App\Models\Modules\Blog\TraitsImport;


use App\Helpers\StrMaster;
use App\Models\Modules\Blog\Import;
use App\Models\Modules\Blog\ImportScenario;
use App\Models\Modules\Blog\Posts;

trait ImportFromUrls
{
    /**
     * @param \App\Models\Modules\Blog\Import $import
     *
     * @return bool
     */
    static public function executeUrls(Import $import) : bool
    {
        $rssUrls = explode("\n", str_replace("\r", "", $import->task_source));
        $countCreatePosts = 0;
        $logLastExecute = '';

        if (!is_array($rssUrls)) {
            $import->log_last_execute = 'Import Error, no rss urls';
            $import->save();

            return false;
        }

        $totalCandidateToImport = count($rssUrls);

        foreach ($rssUrls as $url) {
            $uniqueIdAfterImport = self::createUniqueIdAfterImportUrl($url, $import->category_id, $import->author_id);

            if (Posts::query()->where(['unique_id_after_import' => $uniqueIdAfterImport])->exists()) {
                continue;
            }

            if (!empty($import->skip_url_if_entry)) {
                $arrayEntriesToSkip = explode("\n", $import->skip_url_if_entry);

                if (StrMaster::checkStrInString($url, $arrayEntriesToSkip)) {
                    continue;
                }
            }

            if ($import->what_are_we_doing == self::DOING_REWRITE) { dd('rewrite');
                $result = ImportScenario::rewrite($url, true, explode("\n", $import->skip_if_entries_phrases));
            }

            if ($import->what_are_we_doing == self::DOING_TRANSLATE) { dd('translate');
                $result = ImportScenario::translate($url, true, explode("\n", $import->skip_if_entries_phrases));
            }

            if ($import->what_are_we_doing == self::DOING_TRANSLATE_AND_REWRITE) {
                $result = ImportScenario::translateAndRewrite($url, true, explode("\n", $import->skip_if_entries_phrases));
            }


            $dataToPost = [
                'post_category_id' => $import->category_id,
                'author_id' => $import->author_id,
                'title' => $result['title'], //$item->get_title(),
                'seo_title' => $result['seo_title'], //$item->get_title(),
                'seo_description' => $result['seo_description'], //$item->get_description(),
                'content' => $result['content'], //$item->get_content(),
                'description' => $result['description'], //$item->get_description(),
                'image' => $result['image'],
                'unique_id_after_import' => $uniqueIdAfterImport,
                'status' => $result['result'] ? 'Published' : 'Draft',
                'source_url' => $url,
            ];

            if (Posts::store($dataToPost)) {
                $countCreatePosts++;
                $logLastExecute .= 'Success: ' . $url . '/' . $uniqueIdAfterImport . PHP_EOL;
            } else {
                $logLastExecute .= 'Error: ' . $url . '/' . $uniqueIdAfterImport . PHP_EOL;
            }
        }

        $import->log_last_execute = 'Import ' . $countCreatePosts . ' from ' . $totalCandidateToImport . PHP_EOL . $logLastExecute;
        $import->save();

        return true;
    }
}
