<?php

namespace App\Http\Controllers\Modules\Blog;

use App\Http\Controllers\Controller;
use App\Models\Modules\Blog\Category;
use App\Models\Modules\Blog\Posts;
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

        //dd($topFourPosts);
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
        if (is_null($categoryId = Category::findCategoryIdByUrl(Route::current()->uri()))) {
            return abort('404');
        }

        $posts = Posts::query()->where(['post_category_id' => $categoryId])->paginate();

        return view('modules.blog.category', ['posts' => $posts/*, 'columns' => $columns*/]);
    }

}
