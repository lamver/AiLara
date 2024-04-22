<?php

namespace App\Models\Modules\Blog;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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

        if (is_null($model->slug)) {
            $model->slug = Str::slug($model->title);
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

    static public function updateCategory(Category $model, $data): bool
    {
        $modelParams = self::getModelParams();

        foreach ($modelParams as $params) {
            if (isset($data[$params->Field])) {
                $model->{$params->Field} = $data[$params->Field];
            }
        }

        if (is_null($model->slug)) {
            $model->slug = Str::slug($model->title);
        }

        try {
            return $model->save();
        } catch (\Exception $exception) {
            return false;
        }
    }

    static public function getCategoryTree()
    {
        $categories = self::where('parent_id', '=', null)->get();
        $allCategories = self::pluck('title','id')->all();

        return compact('categories','allCategories');
    }

    /**
     * @return array
     */
    static public function getFullUrlsToAllCategory($child = null, $paths = []): array
    {
        try {
            $data = self::query()->select(['id', 'parent_id','slug'])->get()->toArray();
        } catch (\Exception $e) {
            $data = [];
        }

        return self::buildSlugs($data);
    }

    static public function getFullUrlsToAllChild($childs = null, $paths = [])
    {
        foreach ($childs as $child) {
            if (count($child->childs)) {
                self::getFullUrlsToAllChild($child->childs);
            }
            $paths[] = $child->slug;
        }

        return $paths;
    }

    static public function buildSlugs($data, $parentId = 0, $path = '') {
        $result = [];

        foreach ($data as $row) {
            if ($row['parent_id'] == $parentId) {
                $newPath = $path . $row['slug'] . '/';
                $result[] = $newPath;
                $result = array_merge($result, self::buildSlugs($data, $row['id'], $newPath));
            }
        }

        return $result;
    }

    static public function findCategoryIdByUrl($url) {
        $categories = self::query()->select(['id', 'parent_id','slug'])->get()->toArray();
        $urlParts = explode('/', $url);
        $currentCategoryId = null;

        foreach($urlParts as $slug) {
            foreach($categories as $category) {
                if($category['slug'] === $slug && $category['parent_id'] == $currentCategoryId) {
                    $currentCategoryId = $category['id'];
                    break;
                }
            }
        }

        return $currentCategoryId;
    }

    static public function getCategoryUrlById($category_id) {
        $categories = self::query()->select(['id', 'parent_id','slug'])->get()->toArray();
        $path = [];

        // Находим категорию по id и добавляем slug в начало массива path
        while ($category_id !== null) {
            foreach ($categories as $category) {
                if ($category['id'] == $category_id) {
                    array_unshift($path, $category['slug']);
                    $category_id = $category['parent_id'];
                    break;
                }
            }
        }

        return implode('/', $path);
    }

    /**
     * @return array[]
     */
    static public function getBreadCrumbsByUri($uri): array
    {
        $uriToUri = explode("/", $uri);

        $uriConcat = '';

        $data = [];

        foreach ($uriToUri as $url) {
            if ($uriConcat == '') {
                $uriConcat .= $url;
            } else {
                $uriConcat .= '/' . $url;
            }

            $category = self::findCategoryIdByUrl($uriConcat);

            $category = self::query()->select(['id', 'parent_id','slug', 'title'])->where(['id' => $category])->first();

            $data[] = [
                'name' => $category->title, 'uri' => '/'.$uriConcat,
            ];
        }

        return $data;
    }

    static public function columnName($column = '')
    {
        $column = str_replace("_", " ", $column);
        $column = ucfirst($column);

        return $column;
    }
}
