<?php

namespace App\Http\Controllers\Modules\Blog;

use App\Helpers\ImageMaster;
use App\Helpers\SeoTools;
use App\Http\Controllers\Controller;
use App\Models\Modules\Blog\Category;
use App\Models\Modules\Blog\Posts;
use App\Settings\SettingBlog;
use App\Settings\SettingGeneral;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Spatie\Feed\Feed;
use Throwable;

class PostsController extends Controller
{
    /**
     * The maximum number of items to fetch from an RSS feed.
     *
     * @var int
     */
    const RSS_ITEMS_LENGTH = 200;

    /**
     * Display a listing of the resource.
     * @param Request $request
     */
    public function index(Request $request)
    {
        $topFourPosts = Posts::topFourPosts();

        $unicIds = Posts::getUniqIdsFromCollections($topFourPosts);

        $topPostsDifferentCategories = Posts::topPostsDifferentCategories($unicIds);

        $settings = new SettingGeneral();

        $param = [
            'title'         => $settings->seo_title,
            'description'   => $settings->seo_description,
            'canonicalUrl'  => '/',
            'type'          => 'articles',
        ];

        if (!empty($settings->image)) {
            $param['image'] = ImageMaster::resizeImgFromCdn($settings->image, 300, 300);
        }

        SeoTools::setSeoParam($param);

        return view('modules.blog.index', [
            'topFourPosts' => $topFourPosts,
            'topPostsDifferentCategories' => $topPostsDifferentCategories,
            'settings' => $settings,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Posts $posts)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Posts $posts)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Posts $posts)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Posts $posts)
    {
        //
    }

    public function category(Request $request, SettingBlog $settingBlog)
    {
        if (is_null($categoryId = Category::findCategoryIdByUrl($request->path()))) {
            return abort('404');
        }

        $breadcrumbs = \App\Models\Modules\Blog\Category::getBreadCrumbsByUri($request->path());

        $category = Category::query()->find($categoryId);

        $param = [
            'title'         => $category->seo_title,
            'description'   => $category->seo_description,
            'canonicalUrl'  => Url()->current(),
            'type'          => 'articles',
        ];

        SeoTools::setSeoParam($param);

        $posts = Posts::getPostsByCategoryId($categoryId);

        if ($request->isJson()) {
            return view('modules.blog.category_part', [ 'posts' => $posts,]);
        }

        return view('modules.blog.category', [
            'posts' => $posts,
            'category' => $category,
            'breadcrumbs' => $breadcrumbs,
            'settingBlog' => $settingBlog,
            /*, 'columns' => $columns*/
        ]);
    }

    /**
     * @param Request $request
     * @param $slug
     * @param $id
     * @return \Illuminate\Foundation\Application|View|Factory|Application
     */
    public function view(Request $request, SettingBlog $settingBlog, $slug, $id): \Illuminate\Foundation\Application|View|Factory|Application|RedirectResponse
    {
        if (is_null($post = Posts::query()->where(['status' => 'Published'])->find($id))) {
            return abort(404);
        }

        if (
            $request->get('status') == 'draft'
            && Auth::user()->can('posts.edit')
        ) {
            $post->status = 'Draft';
            $post->save();
            return redirect($request->url());
        }

        if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
            $ifModified = strtotime(substr($_SERVER['HTTP_IF_MODIFIED_SINCE'] ?? '', 5));
            $lastModified = strtotime($post->updated_at);
            if ($ifModified && $ifModified >= $lastModified) {
                header($_SERVER['SERVER_PROTOCOL'] . ' 304 Not Modified');
                exit;
            }
        }

        $uri = str_replace('/' . $slug . '_' . $id, '', $request->path());

        $breadcrumbs = \App\Models\Modules\Blog\Category::getBreadCrumbsByUri($uri);

        $canonicalUrl = Posts::createUrlFromPost($post);

        if ('/' . request()->path() != $canonicalUrl) {
            return redirect($canonicalUrl, 301);
        }

        $breadcrumbs[] = ['name' => $post->title, 'uri' => ''];

        $param = [
            'title'         => $post->seo_title,
            'description'   => $post->seo_description,
            'canonicalUrl'  => $canonicalUrl,
            'type'          => 'articles',
        ];

        if (!empty($post->image)) {
            $param['image'] = ImageMaster::resizeImgFromCdn($post->image, 300, 300);
        }

        SeoTools::setSeoParam($param);

        return view('modules.blog.view', [
            'post' => $post,
            'breadcrumbs' => $breadcrumbs,
            'settingBlog' => $settingBlog,
        ]);
    }

    /**
     * Generate an RSS feed for a specific category.
     *
     * @param Request $request
     * @return Response
     */
    public function rss(Request $request): Response
    {
        if (is_null($categoryId = Category::findCategoryIdByUrl(Route::current()->uri()))) {
            return abort('404');
        }

        $items = Category::where('id', $categoryId)
            ->with(['Posts' => function ($query) {
                $query->where('status', Posts::STATUS[0]);
            }])
            ->limit(self::RSS_ITEMS_LENGTH)->first();

        foreach ($items->Posts as $post) {
            $post->description = strip_tags(str::limit($post->description ?? "", Posts::RSS_CONTENT_LN));
        }

        $rss = new Feed(
            $items->title ?? "",
            $items->Posts,
        $items->slug.'/feed',
            'feed::rss',
            $items->description ?? "",
            'ru',
            $items->image ?? "",
        );

        return $rss->toResponse($request);

    }

    /**
     * Get post data via AJAX request.
     *
     * @param Request $request
     * @return array.
     * @throws Throwable
     */
    public function getPostAjax(Request $request)
    {
        $response = [
            'html' => "",
            'posts' => [],
        ];
        $categoryId = $request->get('categoryId');
        $perPage = $request->get('perPage', 30);
        $currentView = $request->get('currentView', 30);

        $posts = Posts::getPostsByCategoryId($categoryId, $perPage);

        if ($request->isJson()) {

            $response = [
                'html' => view($currentView, ['posts' => $posts,])->render(),
                'posts' => $posts->toArray(),
            ];

        }

        return $response;
    }

}
