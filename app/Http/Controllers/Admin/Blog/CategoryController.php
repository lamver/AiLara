<?php

namespace App\Http\Controllers\Admin\Blog;

use App\Http\Controllers\Controller;
use App\Models\Modules\Blog\Category;
use App\Models\Modules\Blog\Import;
use App\Models\Modules\Blog\Posts;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function index(Request $request)
    {
        $categories = Category::where('parent_id', '=', null)->orWhere('parent_id', '=', 0)->get();
        $allCategories = Category::pluck('title','id')->all();

        return view('admin.modules.blog.category.index', compact('categories','allCategories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View|\Illuminate\Foundation\Application|Factory|Application
    {
        $modelParams = Category::getModelParams();
        $categories = Category::where('parent_id', '=', null)->get();
        $allCategories = Category::pluck('title','id')->all();
        $categoryTree = compact('categories','allCategories');

        return view('admin.modules.blog.category.create', ['modelParams' => $modelParams, 'categoryTree' => $categoryTree]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (Category::store($request->post())) {
            return redirect(route('admin.blog.category.index'));
        }

        return redirect(route('admin.blog.category.create'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        dd('show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        $modelParams = Category::getModelParams();
        $categories = Category::where('parent_id', '=', null)->get();
        $allCategories = Category::pluck('title','id')->all();
        $categoryTree = compact('categories','allCategories');

        return view('admin.modules.blog.category.create', ['modelParams' => $modelParams, 'model' => $category,  'categoryTree' => $categoryTree]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        if (Category::updateCategory($category, $request->post())) {
            return redirect(route('admin.blog.category.index'));
        }

        return redirect(route('admin.blog.category.edit', ['post' => $category]));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        if (
            Category::query()->where(['parent_id' => $category->id])->exists()
            || Posts::query()->where(['post_category_id' => $category->id])->exists()
        ) {
            session()->flash('message_warning', 'There are descendants, you need to delete them first');
        } else {
            $category->delete();
        }

        return redirect(route('admin.blog.category.index'));
    }
}
