<?php

namespace App\Models\Modules\Blog;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Category extends Model
{
    use HasFactory;

    protected $table = 'blog_category';

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

    static public function tree()
    {
        $categories = self::all()->toArray();

        //dd($categories);

        $tree = self::CreateTree($categories);

        dd($tree);

        //dd($categories);
        return self::buildTree($categories);
    }

    /**
     * @param $elements
     * @param $parentId
     * @return array
     */
    static public function buildTree($elements, $parentId = null)
    {
        $branch = [];

        foreach ($elements as $element) {
            if ($element->parent_id == $parentId) {
                $children = self::buildTree($elements, $element->id);

                if ($children) {
                    $element->children = $children;
                }

                $branch[] = $element;
            }
        }

        return $branch;
    }

    static function CreateTree($array, $sub = 0)
    {
        $a = [];

        foreach ($array as $v) {
            if ($sub == $v['parent_id']) {
                $b = self::CreateTree($array, $v['id']);

                if (!empty($b)) {
                    //$a[$v['title']] = $b;
                    $a[$v['id']][$v['title']] = $b;
                } else {
                    $a[$v['id']][$v['title']] = $v;
                }

            }
        }

        return $a;
    }

    public function childs() {
        return $this->hasMany('App\Models\Modules\Blog\Category','parent_id','id') ;
    }
}
