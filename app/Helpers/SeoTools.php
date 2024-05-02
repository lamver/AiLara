<?php

namespace App\Helpers;

use Artesaos\SEOTools\Facades\JsonLd;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Support\Str;

/**
 * Class SeoTools
 * @package App\Helpers
 */
class SeoTools
{
    /**
     * @param array $param
     *      $param = [
                    'title'         => $title,
                    'description'   => $description,
                    'canonicalUrl'  => $canonicalUrl,
                    'image'         => $image
                ];
     */
    public static function setSeoParam(?array $param = [])
    {
        if (isset($param['title'])) {
            SEOMeta::setTitle($param['title']);
            OpenGraph::setTitle($param['title']);
            JsonLd::setTitle($param['title']);
        }

        if (isset($param['description'])) {
            SEOMeta::setDescription(Str::limit(strip_tags(html_entity_decode($param['description'], ENT_QUOTES)), 170));
            OpenGraph::setDescription(strip_tags(html_entity_decode($param['description'], ENT_QUOTES)));
            JsonLd::setDescription(strip_tags(html_entity_decode($param['description'], ENT_QUOTES)));
        }

        if (isset($param['canonicalUrl'])) {
            SEOMeta::setCanonical($param['canonicalUrl']);
            OpenGraph::setUrl($param['canonicalUrl']);
        }

        if (isset($param['image'])) {
            OpenGraph::addImage($param['image'], ['height' => 300, 'width' => 300]);
            JsonLd::addImage($param['image']);
        }

        if (isset($param['keywords'])) {
            SEOMeta::setKeywords($param['keywords']);
        }

        if (isset($param['type'])) {
            OpenGraph::addProperty('type', $param['type']);
        }

        if (isset($param['descriptionContentPage'])) {
            view()->share('descriptionPage', __('dh.'.$param['descriptionContentPage']));
        }
    }

    static public function getDescription()
    {
        return SEOMeta::getDescription();
    }

    static public function getTitle()
    {
        return SEOMeta::getTitle();
    }

    /**
     * @param $filePath
     *
     * @return mixed|string
     */
    static public function getPageDocMarkdown($filePath)
    {
        if (file_exists($filePath)) {
            return  \Illuminate\Support\Facades\Cache::remember($filePath, 86400, function () use ($filePath) {
                return Str::markdown(file_get_contents($filePath));
            });

        }
        return '';
    }
}
