<?php

namespace App\Models\Modules\AiForm;

use App\Services\Modules\Module;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Class AiForm
 *
 * @package App\Models\Modules\AiForm
 */
class AiForm extends Model
{
    use HasFactory;

    //private string $name;
    //private string $form_config;

    protected $fillable = [
        'id',
        'name',
        'form_config',
        'slug',
    ];

    public static function getFormConfig($formId = null, $taskId = null): array
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
                        ],
                        "days_of_week" => [
                            "type" => "select",
                            "options" => [
                                'Пн' => 'Пн',
                                'Вт' => 'Вт',
                            ],
                            "min_limit" => 3,
                            "max_limit" => 100,
                            "required" => false,
                            "placeholder" => 'День недели',
                            "classList" => ['form-select'],
                            "classListParamBlock" => ['col-md-6'],
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
                    ],
                    'prompt_mask' => 'Расскажи что может означать этот сон: {{prompt}} для человека {{sex}} по имени {{name}} в возрасте {{age}}',
                ],
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

    /**
     * @param int $id
     * @return array
     */
    public static function getForm(int $id): array
    {
        $form = self::find($id)->toArray();
        $form['form_config'] = json_decode($form['form_config'], true);

        return $form;
    }

    /**
     * @param $aiFromId
     *
     * @return mixed|string
     */
    static public function fillAiFormRoute($aiFromId)
    {
        $form = self::query()->select(['slug'])->where(['id' => $aiFromId])->first();
        return 'aiform.view.form.'.$form->slug.'.result.task';
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

    /**
     * @param array $data
     * @param \App\Models\Modules\AiForm\AiForm|null $aiFrom
     *
     * @return bool|string
     */
    static public function createOrUpdate(array $data = [], AiForm $aiFrom = null) : bool|string
    {
        if (is_null($aiFrom)) {
            $aiFrom = new self();
        }

        if (
            isset($data['use_default'])
            && $data['use_default'] == 1
        ) {
            self::setDefault($aiFrom);
        }

        if (isset($data['name'])) {
            $aiFrom->name = $data['name'];
        }

        if (isset($data['name']) && is_null($data['name'])) {
            $aiFrom->name = 'New Form';
        }

        if (isset($data['form_config'])) {
            $aiFrom->form_config = $data['form_config'];
        }

        if (array_key_exists('content_on_page', $data)) {
            $aiFrom->content_on_page = $data['content_on_page'];
        }

        if (array_key_exists('seo_title', $data)) {
            $aiFrom->seo_title = $data['seo_title'];
        }

        if (array_key_exists('seo_description', $data)) {
            $aiFrom->seo_description = $data['seo_description'];
        }

        if (array_key_exists('image', $data)) {
            $aiFrom->image = $data['image'];
        }

        if (array_key_exists('price_per_symbol', $data)) {
            $aiFrom->price_per_symbol = $data['price_per_symbol'];
        }

        if (array_key_exists('price_per_execute', $data)) {
            $aiFrom->price_per_execute = $data['price_per_execute'];
        }

        if (array_key_exists('description_on_page', $data)) {
            $aiFrom->description_on_page = $data['description_on_page'];
        }

        if (array_key_exists('title_h1', $data)) {
            $aiFrom->title_h1 = $data['title_h1'];
        }

        if (array_key_exists('title_h2', $data)) {
            $aiFrom->title_h2 = $data['title_h2'];
        }

        if (array_key_exists('posts_ids', $data)) {
            $aiFrom->posts_ids = $data['posts_ids'];
        }

        if (array_key_exists('category_ids', $data)) {
            $aiFrom->category_ids = json_encode($data['category_ids']);
        }

        if (array_key_exists('view_posts', $data)) {
            $aiFrom->view_posts = (bool) $data['view_posts'];
        }

        if (array_key_exists('allow_comments', $data)) {
            $aiFrom->allow_comments = $data['allow_comments'];
        }

        if (array_key_exists('allow_indexing_results', $data)) {
            $aiFrom->allow_indexing_results = $data['allow_indexing_results'];
        }

        $aiFrom->user_id = Auth::id();

        if (empty($aiFrom->name)) {
            $aiFrom->name = 'New form';
        }

        $aiFrom->slug = self::fillSlugFromData($data, $aiFrom);

        try {
            return $aiFrom->save();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * @param $aiForm
     */
    static public function setDefault(&$aiForm)
    {
        self::where('use_default', true)->update(['use_default' => false]);

        $aiForm->use_default = true;
    }

    /**
     * @param array $data
     * @param \App\Models\Modules\AiForm\AiForm $aiFrom
     *
     * @return string
     */
    static public function fillSlugFromData(array $data, AiForm $aiFrom) : string
    {
        if (array_key_exists('slug', $data)) {
            if (!empty($data['slug'])) {
                return $data['slug'];
            }

            if (empty($data['slug']) && !empty($aiFrom->name)) {
                return Str::slug($aiFrom->name);
            }
        }

        if (
            (empty($aiFrom->slug) || $aiFrom->slug == '')
            && !empty($aiFrom->name)
        ) {
            return Str::slug($aiFrom->name);
        }

        return $aiFrom->slug;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    static public function loadDefaultForm()
    {
        $aiFrom = self::query()->where(['use_default' => true])->first();

        if (empty($aiFrom)) {
            $aiFrom = self::query()->first();
        }

        return $aiFrom;
    }

    /**
     * @param string|array $mask
     * @param array $data
     *
     * @return array|string|string[]
     */
    static public function fillPromptMask(string|array $mask, array $data) : array|string
    {
        $keysArray = array_map(function ($key) {
            return '{{' . $key . '}}';
        }, array_keys($data));

        $valuesArray = array_values($data);

        return str_replace($keysArray, $valuesArray, $mask);
    }

    /**
     * @param $formId
     * @param $taskId
     * @param $paramKey
     *
     * @return string
     */
    static public function fillParamName($formId, $taskId, $paramKey) : string
    {
        if (!$form = self::findFormById($formId)) {
            return 'unknown';
        }

        $formConfig = self::formConfigToArray($form);

        if (
            !isset($formConfig['tasks'])
            || !isset($formConfig['tasks'][$taskId])
            || !isset($formConfig['tasks'][$taskId]['params'])
            || !isset($formConfig['tasks'][$taskId]['params'][$paramKey])
            || !isset($formConfig['tasks'][$taskId]['params'][$paramKey]['placeholder'])
        ) {
            return 'unknown';
        }

        return $formConfig['tasks'][$taskId]['params'][$paramKey]['placeholder'];
    }

    /**
     * @param \App\Models\Modules\AiForm\AiForm $form
     *
     * @return mixed
     */
    static public function formConfigToArray(AiForm $form): array|bool
    {
        try {
            $formConfig = json_decode($form->form_config, true);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return false;
        }

        if (!is_array($formConfig)) {
            return false;
        }

        return $formConfig;
    }

    static public function findFormById($formId)
    {
        $form = self::query()->find($formId)->first();

        if (empty($form)) {
            return false;
        }

        return $form;
    }
}
