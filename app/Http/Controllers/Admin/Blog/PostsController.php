<?php

namespace App\Http\Controllers\Admin\Blog;

use App\Http\Controllers\Controller;
use App\Models\Modules\Blog\Category;
use App\Models\Modules\Blog\Posts;
use App\Models\TelegramBot;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function index(Request $request): \Illuminate\Foundation\Application|View|Factory|Application
    {
        $posts = Posts::loadPostsList();
        $columns = Posts::getModelParams();

        return view('admin.modules.blog.index', [
            'posts' => $posts,
            'columns' => $columns,
            'telegramBots' => TelegramBot::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $modelParams = Posts::getModelParams();
        $categories = Category::where('parent_id', '=', null)->get();
        $allCategories = Category::pluck('title','id')->all();
        $categoryTree = compact('categories','allCategories');

        return view('admin.modules.blog.create', [
            'modelParams' => $modelParams,
            'categoryTree' => $categoryTree,
            'telegramBots' => TelegramBot::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (Posts::store($request->post())) {
            return redirect(route('admin.blog.post.index'));
        }

        return redirect(route('admin.blog.post.create'));
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
    public function edit(Request $request, $posts)
    {
        if (!$post = Posts::find($posts)) {
            return redirect(route('admin.blog.post.index'));
        }

        $modelParams = Posts::getModelParams();
        $categories = Category::where('parent_id', '=', null)->get();
        $allCategories = Category::pluck('title','id')->all();
        $categoryTree = compact('categories','allCategories');

        return view('admin.modules.blog.create', [
            'modelParams' => $modelParams,
            'post' => $post,
            'categoryTree' => $categoryTree,
            'telegramBots' => TelegramBot::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $posts)
    {
        if (Posts::updatePost($posts, $request->post())) {
            return redirect(route('admin.blog.post.index'));
        }

        return redirect(route('admin.blog.post.edit', ['post' => $posts]));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Posts $posts, $postId)
    {
        if (!$postModel = Posts::query()->where(['id' => $postId])->first()) {
            return abort('404');
        }

        if (!$postModel->delete()) {
            session()->flash('message_warning', 'Post delete error');
        }

        return redirect(route('admin.blog.post.index'));
    }
}
