<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiForm extends Model
{
    use HasFactory;

    private string $name;
    private string $form_config;

    protected $fillable = [
        'id',
        'name',
        'form_config',
    ];

    public static function getFormConfig($formId = null, $taskId = null)
    {
        $form = [
            'result' => true,
            'tasks' => [
                12 => [
                    'name' => 'Что означает сон',
                    "id" => 12,
                    "price" => 0.006,
                    "btnName" => 'Прокомментируй сон <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-right-fill" viewBox="0 0 16 16"><path d="m12.14 8.753-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z"></path></svg>',
                    "params" => [
                        "prompt" => [
                            "type" => "text",
                            "min_limit" => 3,
                            "max_limit" => 10000,
                            "required" => true,
                            "placeholder" => 'Опишите ваш сон максимально подробно',
                            "classList" => ['form-control'],
                            "classListParamBlock" => ['col-md-12'],
                            "style" => 'margin: 5px;',
                        ],
                        "age" => [
                            "type" => "number",
                            "min_limit" => 5,
                            "max_limit" => 100,
                            "required" => false,
                            "placeholder" => 'Ваш возраст',
                            "classList" => ['form-control'],
                            "classListParamBlock" => ['col-md-2'],
                            "style" => 'margin: 5px;',
                        ],
                        "name" => [
                            "type" => "string",
                            "min_limit" => 3,
                            "max_limit" => 100,
                            "required" => false,
                            "placeholder" => 'Ваше имя',
                            "classList" => ['form-control'],
                            "classListParamBlock" => ['col-md-4'],
                            "style" => 'margin: 5px;',
                        ],
                        "sex" => [
                            "type" => "select",
                            "options" => [
                                'мужской' => 'Мужчина',
                                'женский' => 'Женщина',
                            ],
                            "min_limit" => 3,
                            "max_limit" => 100,
                            "required" => false,
                            "placeholder" => 'Ваш пол',
                            "classList" => ['form-select'],
                            "classListParamBlock" => ['col-md-6'],
                            "style" => 'margin: 5px;',
                        ]
                    ],
                    'prompt_mask' => 'Расскажи что может означать этот сон: {{params}} для человека {{sex}} по имени {{name}} в возрасте {{age}}',
                ],
                /*23 => [
                     'name' => 'Очень сложная задача',
                     "id" => 23,
                     "price" => 0.006,
                     "params" => [
                         "prompt" => [
                             "type" => "text",
                             "min_limit" => 3,
                             "max_limit" => 10000,
                             "required" => true,
                             "placeholder" => 'ferf453454erf',
                             "classList" => ['form-control'],
                             "style" => 'margin: 5px;',
                         ]
                     ]
                 ],*/
            ],
        ];

        if (
            $taskId
            && isset($form['tasks'][$taskId])
        ) {
            return $form['tasks'][$taskId]['params'];
        }

        return $form;
    }

    public static function getPromptMask($formId = null, $taskId = null)
    {
        $form = self::getFormConfig()['tasks'][$taskId];

        if (
            $taskId
            && isset($form['tasks'][$taskId])
            && isset($form['tasks'][$taskId]['prompt_mask'])
        ) {
            return $form['tasks'][$taskId]['prompt_mask'];
        }

        return false;
    }
}
