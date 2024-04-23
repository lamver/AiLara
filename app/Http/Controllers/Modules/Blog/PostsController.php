<?php

namespace App\Http\Controllers\Modules\Blog;

use App\Helpers\SeoTools;
use App\Http\Controllers\Controller;
use App\Models\Modules\Blog\Category;
use App\Models\Modules\Blog\Posts;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param Request $request
     */
    public function index(Request $request)
    {
        $topFourPosts = Posts::topFourPosts();
        $topPostsDifferentCategories = Posts::topPostsDifferentCategories();

        return view('modules.blog.index', [
            'topFourPosts' => $topFourPosts,
            'topPostsDifferentCategories' => $topPostsDifferentCategories
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

    public function category(Request $request)
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

        return view('modules.blog.category', [
            'posts' => $posts,
            'category' => $category,
            'breadcrumbs' => $breadcrumbs,
            /*, 'columns' => $columns*/
        ]);
    }

    /**
     * @param Request $request
     * @param $slug
     * @param $id
     * @return \Illuminate\Foundation\Application|View|Factory|Application
     */
    public function view(Request $request, $slug, $id): \Illuminate\Foundation\Application|View|Factory|Application
    {
        if (is_null($post = Posts::query()->find($id))) {
            return abort(404);
        }

        $uri = str_replace('/' . $slug . '_' . $id, '', $request->path());

        $breadcrumbs = \App\Models\Modules\Blog\Category::getBreadCrumbsByUri($uri);

        $breadcrumbs[] = ['name' => $post->title, 'uri' => 'erffre'];

        $param = [
            'title'         => $post->seo_title,
            'description'   => $post->seo_description,
            'canonicalUrl'  => Url()->current(),
            'type'          => 'articles',
        ];

        SeoTools::setSeoParam($param);

        return view('modules.blog.view', [
            'post' => $post,
            'breadcrumbs' => $breadcrumbs,
        ]);
    }

    public function rss(Request $request)
    {
        if (is_null($categoryId = Category::findCategoryIdByUrl(Route::current()->uri()))) {
            return abort('404');
        }

        $feed = Posts::getFeedItems(); //query()->where(['post_category_id' => $categoryId])->get()->toFeedItem();
        dd($feed, $categoryId, 'rss');
    }

}
