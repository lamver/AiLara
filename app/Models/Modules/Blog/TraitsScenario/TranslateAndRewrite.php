<?php


namespace App\Models\Modules\Blog\TraitsScenario;


use App\Helpers\StrMaster;
use App\Services\AiSearchApi;
use Illuminate\Support\Facades\Log;

trait TranslateAndRewrite
{
    /**
     * @param $text
     * @param bool $url
     * @param string|array|null $skip
     *
     * @return array
     */
    static public function translateAndRewrite($text, bool $url = false, string|array $skip = null): array
    {
        $answer = [
            'result' => true,
            'content' => '',
            'title' => '',
            'seo_title' => '',
            'seo_description' => '',
            'description' => '',
            'image' => '',
        ];

        $task = new AiSearchApi();

        $param = [
            'prompt' => 'Переведи на русский язык эту статью а затем перепиши, старайся писать больше чем в исходнике, оформи результат тегами markdown: ' . PHP_EOL . $text,
        ];

        if ($url) {
            $param['any-task-v2'] = 1;
        }

        $result = $task->taskCreate($param);

        if ($result['result']) {
            $answer['content'] = self::getResult($task, $result['task_id']);
        } else {
            Log::channel('import')->log('warning', $result);
            $answer['result'] = false;

            return $answer;
        }

        if (StrMaster::checkStrInString($answer['content'], $skip)) {
            Log::channel('import')->log('warning', 'skip');
            $answer['result'] = false;
            return $answer;
        }

        // write title
        $param = [
            'prompt' => 'Напиши заголовок для этой статьи: ' . $answer['content'],
        ];

        $result = $task->taskCreate($param);

        if ($result['result']) {
            $answer['title'] = self::getResult($task, $result['task_id']);
        } else {
            Log::channel('import')->log('warning', $result);
            $answer['result'] = false;
        }

        //write seo title
        $param = [
            'prompt' => $answer['content'],
            'type_task' => 20
        ];

        $result = $task->taskCreate($param);

        if ($result['result']) {
            $answer['seo_title'] = self::getResult($task, $result['task_id']);
        } else {
            Log::channel('import')->log('warning', $result);
            $answer['result'] = false;
        }

        //write seo description
        $param = [
            'prompt' => $answer['content'],
            'type_task' => 21
        ];

        $result = $task->taskCreate($param);

        if ($result['result']) {
            $answer['seo_description'] = self::getResult($task, $result['task_id']);
        } else {
            Log::channel('import')->log('warning', $result);
            $answer['result'] = false;
        }

        //write description
        $param = [
            'prompt' => 'Выпиши основную суть статьи: ' . $answer['content'],
        ];

        $result = $task->taskCreate($param);

        if ($result['result']) {
            $answer['description'] = self::getResult($task, $result['task_id']);
        } else {
            $answer['result'] = false;
            $answer['description'] = 'error';
        }

        $typeImage = [
            'Иллюстрация',
            'Фото',
            'Изображение',
        ];

        //create image
        $param = [
            'prompt' => $typeImage[array_rand($typeImage)] . ' для статьи с заголовком: ' . $answer['seo_title'],
            'basic' => 1,
            'size' => "1024x1024",
            'type_task' => 2,
        ];

        $result = $task->taskCreate($param);

        if ($result['result']) {
            $filesUrl = self::getResult($task, $result['task_id'], true);

            if (empty($filesUrl)) {
                $answer['result'] = false;
                $answer['image'] = 'error';
            } else {
                $answer['image'] = $filesUrl[array_rand($filesUrl)]; //array_rand($filesUrl, 0); //$filesUrl;
            }

        } else {
            $answer['result'] = false;
            $answer['image'] = 'error';
        }

        return $answer;
    }
}
