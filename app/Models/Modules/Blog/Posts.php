<?php

namespace App\Models\Modules\Blog;

use App\Helpers\StrMaster;
use App\Models\TelegramBot;
use App\Models\telegramMessages;
use App\Models\User;
use App\Observers\BlogPostsObserver;
use App\Traits\Commentable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Feed\Feedable;
use Spatie\Feed\FeedItem;

#[ObservedBy([BlogPostsObserver::class])]
class Posts extends Model implements Feedable
{
    use HasFactory;
    use Commentable;

    const STATUS = [
        'Published',
        'Draft'
    ];

    /**
     * The maximum number of characters for the content of an RSS feed item.
     *
     * @var int
     */
    const RSS_CONTENT_LN = 200;

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
            if (array_key_exists($params->Field, $data)) {
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
            if (array_key_exists($params->Field, $data)) {
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
        return self::createUrlToPosts(self::select('id', 'post_category_id', 'author_id', 'title', 'content', 'image', 'updated_at')
            ->where(['status' => 'Published'])
            ->distinct('category_id')
            ->inRandomOrder()
            ->limit(4)
            ->get());
    }

    /**
     * @param array $excludeIds
     *
     * @return mixed
     */
    static public function topPostsDifferentCategories(array $excludeIds = []): mixed
    {
        $ids = implode(",", $excludeIds);

        $result = self::select('id', 'post_category_id', 'title', 'seo_title', 'author_id', 'content', 'image', 'updated_at')
                      ->where(['status' => 'Published']);

        if (!empty($ids)) {
            $result->whereRaw('id not in ('.$ids .')');
        }

        $result = $result->distinct('category_id')
                      ->orderBy('id', 'DESC')
                        /*->inRandomOrder()*/
                      ->limit(14)
                      ->get();

        return self::createUrlToPosts($result);
    }

    /**
     * @param int $lastId
     * @param int $limit
     * @return array
     */
    static public function loadPosts(null|int $lastId = 0, int $limit = 20)
    {
        $result = self::select('id', 'post_category_id', 'title', 'seo_title', 'content', 'image', 'updated_at', 'author_id')
            ->where(['status' => 'Published'])
            ->orderBy('id', 'DESC');

        if (empty($lastId) || $lastId != 0) {
            $result->where('id', '<', $lastId);
        }

        if ($limit > 50) {
            $limit = 50;
        }

        $posts = $result->limit($limit)->get();

        return self::prepareToPreview($posts);
    }

    /**
     * @param $posts
     * @return array
     */
    static public function prepareToPreview($posts): array
    {
        $prepared = [];

        foreach ($posts as $post) {
            $prepared[] = [
                'id' => $post->id,
                'post_category_id' => $post->post_category_id,
                'title' => StrMaster::htmlTagClear($post->title, 50),
                'seo_title' => $post->seo_title,
                'content' => StrMaster::htmlTagClear($post->content, 100),
                'image' => $post->image,
                'updated_at' => $post->updated_at,
                'updated_at_human' => Carbon::create($post->updated_at)->shortRelativeDiffForHumans(date("Y-m-d h:i:s", time())),
                'author_id' => $post->author_id,
                'author_username' => $post->user->name,
                'urlToPost' => self::createUrlFromPost($post),
            ];
        }

        return $prepared;
    }

    /**
     * @param int|array $categoryId
     * @return mixed
     */
    static public function getPostsByCategoryId(int|array $categoryId, $perPage = 30): mixed
    {
        if (is_array($categoryId) && !empty($categoryId)) {
            return self::createUrlToPosts(
                Posts::query()
                    ->whereRaw('post_category_id IN ('.implode(',', $categoryId).')')
                    ->where(['status' => 'Published'])
                    ->orderBy('id', 'DESC')
                    ->paginate($perPage)
            );
        }

        return self::createUrlToPosts(
            Posts::query()
                ->where(['post_category_id' => $categoryId])
                ->where(['status' => 'Published'])
                ->orderBy('id', 'DESC')
                ->paginate($perPage)
        );
    }

    /**
     * @param $posts
     * @return mixed
     */
    static public function createUrlToPosts($posts): mixed
    {
        foreach ($posts as $post) {
            $post->urlToPost = self::createUrlFromPost($post);
        }

        return $posts;
    }

    static public function createUrlFromPost(Posts $post)
    {
        return Category::getCategoryUrlById($post->post_category_id) . '/' . Str::slug(Str::limit(strip_tags($post->title))) . '_' .$post->id;
    }

    /**
     * @return string
     */
    public function currentPostUrl(): string
    {
        return Category::getCategoryUrlById($this->post_category_id) . '/' . Str::slug(Str::limit(strip_tags($this->title))) . '_' .$this->id;
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
        $user = $this->user()->first();

        return FeedItem::create()
            ->id($this->id)
            ->title($this->title ?? "")
            ->summary($this->description ?? "")
            ->updated($this->updated_at)
            ->link(self::createUrlFromPost($this))
            ->image($this->image ?? "")
            ->category($this->category->title)
            ->authorName($user->name ?? "")
            ->authorEmail('');
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

    /**
     * @return BelongsTo
     */
    public function telegramBot(): BelongsTo
    {
        return $this->belongsTo(TelegramBot::class, 'telegram_bot_id', 'id');
    }

    /**
     * @return MorphMany
     */
    public function telegramMessages(): MorphMany
    {
        return $this->morphMany(telegramMessages::class, 'model');
    }

    static public function getUniqIdsFromCollections(Collection $collections)
    {
        $ids = [];

        foreach ($collections as $item) {
            if (isset($item->id)) {
                array_push($ids, $item->id);
            }
        }

        return $ids;
    }
}
