<?php

namespace App\Models\Modules\Blog;

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
    use HasFactory;

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
        self::SOURCE_TYPE_RSS => 'RSS',
        self::SOURCE_TYPE_URL => 'URL',
        self::SOURCE_TYPE_WRITE_LONGREAD => 'WRITE LONGREAD',
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

    static public function execute(Import $import)
    {
        if($import->source_type == Import::SOURCE_TYPE_RSS) {
            self::executeRss($import);
        }
    }

    /**
     * @param Import $import
     * @return true
     */
    static public function executeRss(Import $import)
    {
        $tmpFileNameRss = self::getTmpFileNameRss();

        self::downloadRssFeed($import->task_source, $tmpFileNameRss);

        $tmpFilePath = Storage::path(self::TEMP_LOC_PATH_RSS);

        if (!is_dir($tmpFilePath)) {
            mkdir($tmpFilePath, 0744, true);
        }

        $tempPathToRss = $tmpFilePath . $tmpFileNameRss;

        $tempPathToRssUrl = request()->getSchemeAndHttpHost() . '/temp_import_rss?temp_file=' . base64_encode($tempPathToRss);

        if (request()->getHttpHost() == 'localhost:4434') {
            $tempPathToRssUrl = $tempPathToRss;
        }

//dd(request()->getSchemeAndHttpHost(), $tempPathToRssUrl);
        $feed = FeedsFacade::make($tempPathToRssUrl, true);
        //dd($feed->get_items(), $feed, $tempPathToRss);
        $totalCandidateToImport = count($feed->get_items());
        $countCreatePosts = 0;
        $logLastExecute = '';

        foreach ($feed->get_items() as $item) {

            $uniqueIdAfterImport = self::createUniqueIdAfterImportRss($item, $import->category_id, $import->author_id);

            if (Posts::query()->where(['unique_id_after_import' => $uniqueIdAfterImport])->exists()) {
                continue;
            }

            if ($import->what_are_we_doing == self::DOING_REWRITE) {
                $result = ImportScenario::rewrite($item->get_permalink(), true);
            }

            if ($import->what_are_we_doing == self::DOING_TRANSLATE) {
                $result = ImportScenario::translate($item->get_permalink(), true);
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
        }

        if (file_exists($tempPathToRss)) {
/*            if (!unlink($tempPathToRss)) {
                $logLastExecute .= 'error delete file: ' . $tempPathToRss . PHP_EOL;
            }*/
        }

        $import->log_last_execute = 'Import ' . $countCreatePosts . ' from ' . $totalCandidateToImport . PHP_EOL . $logLastExecute;
        $import->save();

        return true;
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

    /**
     * @param $fileUrl
     * @param $tmpFileName
     * @return bool
     */
    static public function downloadRssFeed($fileUrl, $tmpFileName)
    {
        $curl = curl_init();
        //$proxy = 'socks5://proxy_ip:proxy_port'; // Замените на реальные данные прокси
        $fileUrl = $fileUrl;
        $savePath = Storage::path(self::TEMP_LOC_PATH_RSS); // Путь для сохранения файла

        curl_setopt($curl, CURLOPT_PROXY, Proxy::getProxyString());
        curl_setopt($curl, CURLOPT_URL, $fileUrl);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $fileContent = curl_exec($curl);
        Log::channel('import')->log('warning', 'curl_exec '.$fileContent);
        if ($fileContent !== false) {
            if (!is_dir($savePath)) {
                mkdir($savePath, 755, true);
            }
            file_put_contents($savePath . '/' . $tmpFileName, $fileContent);
            Log::channel('import')->log('warning', 'file_put_contents true');
            return true;
        } else {
            Log::channel('import')->log('warning', 'file_put_contents true');
            return false;
        }
        Log::channel('import')->log('warning', 'here');
        curl_close($curl);
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
