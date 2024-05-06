<?php

namespace App\Http\Requests;

use App\Models\AiForm;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class TaskExecuteRequest extends FormRequest
{
    protected function failedValidation(Validator $validator) {
        $this->failedValidationAnswerJson($validator->errors());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        if (
            isset($_POST['form_id'])
            && isset($_POST['task_id'])
        ) {
            $aiForm = \App\Models\Modules\AiForm\AiForm::getFormConfig($_POST['form_id']);
            if (!isset($aiForm['tasks'][$_POST['task_id']])) {
                $this->failedValidationAnswerJson(['task_id' => $_POST['task_id'] . 'task_id not found']);
            }

            $rules = [];

            foreach ($aiForm['tasks'][$_POST['task_id']]['params'] as $paramName => $values) {
                $stringRule = [];

                if ($values['required']) {
                    $stringRule[] = 'required';
                }

                if ($values['type'] == 'number') {
                    $stringRule[] = 'int';
                }

                if (isset($values['min_limit'])) {
                    $stringRule[] = 'min:'.$values['min_limit'];
                }

                if (isset($values['max_limit'])) {
                    $stringRule[] = 'max:'.$values['max_limit'];
                }

                $rules[$paramName] = implode("|", $stringRule);
            }

            return $rules;
            //dd($rules);
        } else {
            return [
                'form_id' => 'required|int',
                'task_id' => 'required|int'
            ];
        }
    }

    private function failedValidationAnswerJson($message)
    {
        $result = [
            'result' => false,
            'errors' => $message
        ];

        throw new HttpResponseException(response()->json($result, 422));
    }
}
