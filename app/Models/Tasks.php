<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Class Tasks
 *
 * @package App\Models
 */
class Tasks extends Model
{
    use HasFactory;

    const STATUS_CREATED = 1;
    const STATUS_IN_PROGRESS = 2;
    const STATUS_DONE_SUCCESSFULLY = 3;
    const STATUS_PARTIALLY_COMPLETED = 4;
    const STATUS_DONE_ERROR = 5;

    const ACCESS_TYPE_PUBLIC = 1;
    const ACCESS_TYPE_PRIVATE = 2;
    const ACCESS_TYPE_PUBLIC_URL = 3;
    const ACCESS_TYPE_PUBLIC_BY_USER = 4;

    const TYPE_ANSWER_TEXT = 1;
    const TYPE_ANSWER_JSON = 2;
    const TYPE_ANSWER_URL_TO_FILE = 3;

    protected $fillable = [
        'form_id',
        'task_id',
        'user_params',
    ];

    public static function createTask(array $param)
    {
        $taskData = [];
        $taskData['form_id'] = $param['form_id'];
        $taskData['task_id'] = $param['task_id'];
        $formParams = AiForm::getFormConfig($param['form_id'], $param['task_id']);
        $paramFromForm = array_keys($formParams);
        $userParams = [];

        foreach ($paramFromForm as $paramForm => $valueForm) {
            if (isset($param[$valueForm])) {
                $userParams[$valueForm] = $param[$valueForm];
            }
        }

        $taskData['user_params'] = json_encode($userParams);

        if ($promptMask = AiForm::getPromptMask($param['form_id'], $param['task_id'])) {
            dd($promptMask);
        }



        return self::create($taskData);
    }

    /**
     * @param $param
     *
     * @return string
     */
    public static function createSlugFromUserParams($param) : string
    {
        $formParams = AiForm::getFormConfig($param['form_id'], $param['task_id']);
        $paramFromForm = array_keys($formParams);
        $userParams = [];

        foreach ($paramFromForm as $paramForm => $valueForm) {
            if (isset($param[$valueForm])) {
                $userParams[$valueForm] = str_replace("\n", "", Str::limit($param[$valueForm], 200));
            }
        }

        return Str::slug(implode("_", $userParams));
    }

    /**
     * @param Tasks $params
     * @return string
     */
    public static function createSlug(Tasks $params): string
    {
        $userParams = json_decode($params->user_params, true);
        $userParams = array_map(fn($val) => str_replace("\n", "", Str::limit($val, 200)), $userParams);

        return Str::slug(implode("_", $userParams)) . "/" . $params['id'];
    }

}
