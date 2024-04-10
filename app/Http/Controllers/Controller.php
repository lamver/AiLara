<?php

namespace App\Http\Controllers;

use Artesaos\SEOTools\Facades\JsonLd;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function index(Request $request)
    {
        $title = 'ИИ сонник: нейросеть раскрывает значение сна и его тайны';
        $description = 'Нейросеть Сонник - это удобный онлайн сервис, который поможет вам разгадать тайны ваших снов. Здесь вы сможете получить толкование сна и узнать его значение. Нейросеть анализирует ваши сны и даст вам исчерпывающий ответ на вопрос "к чему снится". Используйте ИИ сонник онлайн.';

        SEOMeta::setTitle($title);
        OpenGraph::setTitle($title);
        JsonLd::setTitle($title);

        SEOMeta::setDescription($description);
        OpenGraph::setDescription($description);
        JsonLd::setDescription($description);

        SEOMeta::setKeywords('сонник, толкование снов, к чему снится');

        return view('welcome', ['slot' => 'dd']);
    }
}
