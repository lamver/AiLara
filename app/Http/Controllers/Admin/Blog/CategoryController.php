<?php

namespace App\Http\Controllers\Admin\Blog;

use App\Http\Controllers\Controller;
use App\Models\Modules\Blog\Category;
use App\Models\Modules\Blog\Import;
use App\Models\Modules\Blog\Posts;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
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
        $categoriesAll = Category::all()->sortBy('sort_order');
        $categories = $this->categoryTree($categoriesAll);

        return view('admin.modules.blog.category.index', compact('categories'));
    }

    /**
     * Generate a nested category tree from the provided comments array.
     *
     * @param Collection $comments
     * @param int|null $parentId
     * @return array
     */
    public function categoryTree(Collection $comments, int $parentId = null): array
    {
        $tree = [];

        foreach ($comments as $comment) {

            if ((int) $comment['parent_id'] === (int)$parentId) {
                $children = $this->categoryTree($comments, $comment['id']);
                if ($children) {
                    $comment['children'] = $children;
                }
                $tree[] = $comment;
            }

        }

        return $tree;
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

    public function sort(Request $request)
    {
        //$category = Category::find((int)$request->get('moveId'))->get();
        $category = Category::query()->where(['id' => (int)$request->get('moveId')])->first();

        if ($category) {
            $category->parent_id = (int) $request->get('parentId');
           // $category->sort_order = (int) $request->get('sortOrder');
            $category->save();
        }

        foreach ($request->get('sortOrder') as $sortOrder ) {
            Category::where('id', $sortOrder['id'])->update(['sort_order' => $sortOrder['sortOrder']]);
        }

        dd( $request->get('sortOrder'), $category );

    }
}
