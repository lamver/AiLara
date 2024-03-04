<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Ajax\AiFormController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/form/config', function () {
    return [
        'result' => true,
        'tasks' => [
            12 => [
                'name' => 'Что означает сон',
                "id" => 12,
                "price" => 0.006,
                "params" => [
                    "prompt" => [
                        "type" => "text",
                        "min_limit" => 3,
                        "max_limit" => 10000,
                        "required" => true,
                        "placeholder" => 'Опишите ваш сон максимально подробно',
                        "classList" => ['form-control'],
                        "style" => 'margin: 5px;',
                    ],
                    "age" => [
                        "type" => "number",
                        "min_limit" => 3,
                        "max_limit" => 100,
                        "required" => true,
                        "placeholder" => 'Ваш возраст',
                        "classList" => ['form-control'],
                        "style" => 'margin: 5px;',
                    ]
                ],
            ],
            23 => [
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
            ],
        ],
    ];
});

Route::get('/form/template', [AiFormController::class, 'template'])->name('ajax.ai-form.template');
Route::get('/form/js', [AiFormController::class, 'js'])->name('ajax.ai-form.js');
Route::post('/task/execute', [AiFormController::class, 'execute'])->name('ajax.ai-form.execute');
