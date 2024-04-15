<?php

namespace App\Http\Controllers\Admin\Blog;

use App\Http\Controllers\Controller;
use App\Models\Modules\Blog\Posts;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

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

        $columns = Posts::getModelParams();// Schema::getColumnListing((new Posts)->getTable());

        return view('admin.modules.blog.index', ['posts' => $posts, 'columns' => $columns]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $modelParams = Posts::getModelParams();

        return view('admin.modules.blog.create', ['modelParams' => $modelParams]);
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

        return view('admin.modules.blog.create', ['modelParams' => $modelParams, 'post' => $post]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $posts)
    {
        //dd($request->post(), $posts);

        if (Posts::updatePost($posts, $request->post())) {
            return redirect(route('admin.blog.post.index'));
        }

        return redirect(route('admin.blog.post.edit', ['post' => $posts]));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Posts $posts)
    {
        dd($posts);
    }
}
