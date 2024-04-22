<?php

namespace App\Models\Modules\Blog;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Spatie\Feed\Feedable;
use Spatie\Feed\FeedItem;

class Posts extends Model implements Feedable
{
    use HasFactory;

    const STATUS = [
        'Published',
        'Draft'
    ];

    const STATUS_DEFAULT = 'Draft';

    protected $table = 'blog_posts';

    protected $fillable = [
        'title',
        'author_id',
    ];

    public string $urlToPost;

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
        return self::createUrlToPosts(self::select('id', 'post_category_id', 'title', 'content')
            ->distinct('category_id')
            ->inRandomOrder()
            ->limit(4)
            ->get());
    }

    /**
     * @return mixed
     */
    static public function topPostsDifferentCategories(): mixed
    {
        return self::createUrlToPosts(self::select('id', 'post_category_id', 'title', 'content', 'image')
            ->where(['status' => 'Published'])
            ->distinct('category_id')
            ->inRandomOrder()
            ->limit(20)
            ->get());
    }

    static public function getPostsByCategoryId($categoryId)
    {
        return self::createUrlToPosts(Posts::query()->where(['post_category_id' => $categoryId])->where(['status' => 'Published'])->orderBy('id', 'DESC')->paginate(30));
    }

    /**
     * @param $posts
     * @return mixed
     */
    static public function createUrlToPosts($posts)
    {
        foreach ($posts as $post) {
            $post->urlToPost = '/'.Category::getCategoryUrlById($post->post_category_id) . '/' . Str::slug(Str::limit(strip_tags($post->title))) . '_' .$post->id;
        }

        return $posts;
    }

    static public function getUrlPostById($id)
    {
        if (is_null($post = self::query()->find($id))) {
            return false;
        }

        return Category::getCategoryUrlById($post->post_category_id . '/' );
    }

    /**
     * @return FeedItem
     */
    public function toFeedItem(): FeedItem
    {
        return FeedItem::create()
            ->id($this->id)
            ->title($this->title)
            ->summary($this->summary)
            ->updated($this->updated_at)
            ->link($this->link)
            ->authorName($this->author)
            ->authorEmail($this->authorEmail);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getFeedItems()
    {
        return self::all();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'post_category_id', 'id');
    }
}
