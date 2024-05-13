<?php

namespace App\Models\Modules\Blog;

use App\Helpers\StrMaster;
use App\Models\Modules\Blog\TraitsImport\ImportFromUrls;
use App\Models\User;
use App\Services\Proxy\Proxy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use willvincent\Feeds\Facades\FeedsFacade;

class Import extends Model
{
    use HasFactory, ImportFromUrls;

    const SOURCE_TYPE_RSS = 1;
    const SOURCE_TYPE_URL = 2;
    const SOURCE_TYPE_WRITE_LONGREAD = 3;

    const STATUS_JOB_CREATED = 0;
    const STATUS_JOB_PROCESSED = 1;
    const STATUS_JOB_FINALE_SUCCESS = 2;
    const STATUS_JOB_ERROR = 3;

    const IMPORT_STATUS = [
        self::STATUS_JOB_CREATED,
        self::STATUS_JOB_PROCESSED,
        self::STATUS_JOB_FINALE_SUCCESS,
        self::STATUS_JOB_ERROR,
    ];

    const IMPORT_STATUS_NAME = [
        self::STATUS_JOB_CREATED => 'Created',
        self::STATUS_JOB_PROCESSED => 'Processed',
        self::STATUS_JOB_FINALE_SUCCESS => 'Success',
        self::STATUS_JOB_ERROR => 'Error',
    ];

    const IMPORT_SOURCE_TYPE = [
        self::SOURCE_TYPE_RSS,
        self::SOURCE_TYPE_URL,
        self::SOURCE_TYPE_WRITE_LONGREAD,
    ];

    const IMPORT_SOURCE_TYPE_NAME = [
        self::SOURCE_TYPE_RSS => "RSS's",
        self::SOURCE_TYPE_URL => "URL's",
        self::SOURCE_TYPE_WRITE_LONGREAD => "WRITE LONGREAD's",
    ];

    const DOING_WRITE = 1;
    const DOING_REWRITE = 2;
    const DOING_TRANSLATE = 3;
    const DOING_TRANSLATE_AND_REWRITE = 4;

    const DOING_VARIANTS = [
        self::DOING_WRITE,
        self::DOING_REWRITE,
        self::DOING_TRANSLATE,
        self::DOING_TRANSLATE_AND_REWRITE,
    ];

    const DOING_TITLE = [
        self::DOING_WRITE => 'Напиши текст на эту тему',
        self::DOING_REWRITE => 'Перепиши этот текст',
        self::DOING_TRANSLATE => 'Переведи этот текст',
        self::DOING_TRANSLATE_AND_REWRITE => 'Переведи а затем перепиши',
    ];

    const TEMP_LOC_PATH_RSS = 'public/temp/import/rss/';

    protected $table = 'blog_import_job';

    /**
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    static public function loadPostsList()
    {
        return self::query()->orderBy('id','desc')->paginate();
    }

    /**
     * @return array
     */
    static public function getModelParams()
    {
        return DB::select("show full columns from " . (new self)->table);
    }

    /**
     * @param $data
     * @return bool
     */
    static public function store($data): bool
    {
        $modelParams = self::getModelParams();
        $model = new self();

        foreach ($modelParams as $params) {
            if (isset($data[$params->Field])) {
                $model->{$params->Field} = $data[$params->Field];
            }
        }

        try {
            return $model->save();
        } catch (\Exception $exception) {

            return false;
        }
    }

    /**
     * @param Import $model
     * @param $data
     * @return bool
     */
    static public function updatePost(Import $model, $data): bool
    {
        $modelParams = self::getModelParams();

        foreach ($modelParams as $params) {
            if (isset($data[$params->Field])) {
                $model->{$params->Field} = $data[$params->Field];
            }
        }

        try {
            return $model->save();
        } catch (\Exception $exception) {
            return false;
        }
    }

    static public function getStatusName($status)
    {
        if (isset(self::IMPORT_STATUS_NAME[$status])) {
            return self::IMPORT_STATUS_NAME[$status];
        }

        return 'UNKNOWN';
    }

    static public function getSourceTypeName($sourceType)
    {
        if (isset(self::IMPORT_SOURCE_TYPE_NAME[$sourceType])) {
            return self::IMPORT_SOURCE_TYPE_NAME[$sourceType];
        }

        return 'UNKNOWN';
    }

    static public function getDoingVariantsName($variant)
    {
        if (isset(self::DOING_TITLE[$variant])) {
            return self::DOING_TITLE[$variant];
        }

        return 'UNKNOWN';
    }

    /**
     * @param Import $import
     * @return void
     */
    static public function execute(Import $import): void
    {
        if ($import->source_type == Import::SOURCE_TYPE_RSS) {
            self::executeRss($import);
        }

        if ($import->source_type == Import::SOURCE_TYPE_URL) {
            self::executeUrls($import);
        }
    }

    /**
     * @param Import $import
     * @return true
     */
    static public function executeRss(Import $import): bool
    {
        $tmpFileNameRss = self::getTmpFileNameRss();
        $countCreatePosts = 0;
        $logLastExecute = '';

        $rssUrls = explode("\n", str_replace("\r", "", $import->task_source));


        if (!is_array($rssUrls)) {
            $import->log_last_execute = 'Import Error, no rss urls';
            $import->save();

            return false;
        }

        foreach ($rssUrls as $rssUrl) {
            self::downloadRssFeed($rssUrl, $tmpFileNameRss);

            $tmpFilePath = Storage::path(self::TEMP_LOC_PATH_RSS);

            if (!is_dir($tmpFilePath)) {
                mkdir($tmpFilePath, 0744, true);
            }

            $tempPathToRss = $tmpFilePath . $tmpFileNameRss;

            $feed = FeedsFacade::make($tempPathToRss, true);

            $totalCandidateToImport = count($feed->get_items());

            $logLastExecute .= self::processingRssFeed($feed, $import, $logLastExecute, $countCreatePosts);

            if (file_exists($tempPathToRss)) {
                if (!unlink($tempPathToRss)) {
                    $logLastExecute .= 'error delete file: ' . $tempPathToRss . PHP_EOL;
                }
            }
        }

        $import->log_last_execute = 'Import ' . $countCreatePosts . ' from ' . $totalCandidateToImport . PHP_EOL . $logLastExecute;
        $import->save();

        return true;
    }

    /**
     * @param $feed
     * @param Import $import
     * @param $logLastExecute
     * @param $countCreatePosts
     * @return string|void
     */
    static public function processingRssFeed($feed, Import $import, $logLastExecute, &$countCreatePosts)
    {
        foreach ($feed->get_items() as $item) {

            if (!empty($import->skip_url_if_entry)) {
                $arrayEntriesToSkip = explode("\n", $import->skip_url_if_entry);

                if (StrMaster::checkStrInString($item->get_permalink(), $arrayEntriesToSkip)) {
                    continue;
                }
            }

            $uniqueIdAfterImport = self::createUniqueIdAfterImportRss($item, $import->category_id, $import->author_id);

            if (Posts::query()->where(['unique_id_after_import' => $uniqueIdAfterImport])->exists()) {
                continue;
            }

            if ($import->what_are_we_doing == self::DOING_REWRITE) {
                $result = ImportScenario::rewrite($item->get_permalink(), true, explode("\n", $import->skip_if_entries_phrases));
            }

            if ($import->what_are_we_doing == self::DOING_TRANSLATE) {
                $result = ImportScenario::translate($item->get_permalink(), true, explode("\n", $import->skip_if_entries_phrases));
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
                'source_url' => $item->get_permalink(),
            ];

            if (Posts::store($dataToPost)) {
                $countCreatePosts++;
                $logLastExecute .= 'Success: ' . $item->get_title() . '/' . $uniqueIdAfterImport . PHP_EOL;
            } else {
                $logLastExecute .= 'Error: ' . $item->get_title() . '/' . $uniqueIdAfterImport . PHP_EOL;
            }

            return $logLastExecute;
        }
    }

    /**
     * @param $item
     * @param $categoryId
     * @param $authorId
     * @return string
     */
    static public function createUniqueIdAfterImportRss($item, $categoryId, $authorId)
    {
        return md5($item->get_permalink() . $categoryId . $authorId);
    }

    static public function createUniqueIdAfterImportUrl($url, $categoryId, $authorId)
    {
        return md5($url . $categoryId . $authorId);
    }

    /**
     * @param $fileUrl
     * @param $tmpFileName
     * @return bool
     */
    static public function downloadRssFeed($fileUrl, $tmpFileName)
    {
        $curlOptions = [
            CURLOPT_URL => $fileUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_CONNECTTIMEOUT => 10
        ];

        $result = Proxy::curl($curlOptions);
        $result = json_decode($result, true);

        if (!$result['result']) {
            return true;
        }

        $fileContent = $result['answer'][0];
        $savePath = Storage::path(self::TEMP_LOC_PATH_RSS);

        if ($fileContent !== false) {
            if (!is_dir($savePath)) {
                mkdir($savePath, 755, true);
            }

            file_put_contents($savePath . '/' . $tmpFileName, $fileContent);

            return true;
        } else {
            return false;
        }
    }

    static public function getTmpFileNameRss()
    {
        return rand(10000, 90000) . '.rss';
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }


}
