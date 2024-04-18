<?php

namespace App\Models\Modules\Blog;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Posts extends Model
{
    use HasFactory;

    const STATUS = [
        'Published',
        'Draft'
    ];

    const STATUS_DEFAULT = 'Draft';

    protected $table = 'blog_posts';

    protected $fillable = [
        'title'
    ];

    /**
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    static public function loadPostsList()
    {
        return self::query()->orderBy('id','desc')->paginate();
    }

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

    /**
     * @param $id
     * @param $data
     * @return bool
     */
    static public function updatePost($id, $data): bool
    {
        $modelParams = self::getModelParams();

        $model = Posts::find($id);

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

    static public function topFourPosts()
    {
        return self::select('id', 'post_category_id', 'title', 'content')
            ->distinct('category_id')
            ->inRandomOrder()
            ->limit(4)
            ->get();
    }

    static public function topPostsDifferentCategories()
    {
        return self::select('id', 'post_category_id', 'title', 'content')
            ->distinct('category_id')
            ->inRandomOrder()
            ->limit(20)
            ->get();
    }
}
