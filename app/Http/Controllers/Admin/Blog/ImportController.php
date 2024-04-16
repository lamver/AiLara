<?php

namespace App\Http\Controllers\Admin\Blog;

use App\Http\Controllers\Controller;
use App\Models\Modules\Blog\Category;
use App\Models\Modules\Blog\Import;
use App\Models\Modules\Blog\Posts;
use Illuminate\Http\Request;

class ImportController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param Request $request
     */
    public function index(Request $request)
    {
        $posts = Import::loadPostsList();
        $columns = Import::getModelParams();

        return view('admin.modules.blog.import.index', ['imports' => $posts, 'columns' => $columns]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $modelParams = Import::getModelParams();
        $categories = Category::where('parent_id', '=', null)->get();
        $allCategories = Category::pluck('title','id')->all();
        $categoryTree = compact('categories','allCategories');

        return view('admin.modules.blog.import.create', ['modelParams' => $modelParams, 'categoryTree' => $categoryTree]);
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
    public function show(Import $import)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Import $import)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Import $import)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Import $import)
    {
        //
    }
}
