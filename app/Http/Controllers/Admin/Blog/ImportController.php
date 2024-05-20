<?php

namespace App\Http\Controllers\Admin\Blog;

use App\Http\Controllers\Controller;
use App\Models\Modules\Blog\Category;
use App\Models\Modules\Blog\Import;
use App\Models\TelegramBot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Class ImportController
 *
 * @package App\Http\Controllers\Admin\Blog
 */
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

        return view('admin.modules.blog.import.create', [
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
        if (Import::store($request->post())) {
            return redirect(route('admin.blog.import.index'));
        }

        return redirect(route('admin.blog.import.create'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Import $import)
    {
        if (request()->get('execute')) {
            Import::execute($import);
        }

        $modelParams = Import::getModelParams();
        $categories = Category::where('parent_id', '=', null)->get();
        $allCategories = Category::pluck('title','id')->all();
        $categoryTree = compact('categories','allCategories');

        return view('admin.modules.blog.import.show', ['modelParams' => $modelParams, 'import' => $import, 'categoryTree' => $categoryTree]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Import $import)
    {
        $modelParams = Import::getModelParams();
        $categories = Category::where('parent_id', '=', null)->get();
        $allCategories = Category::pluck('title','id')->all();
        $categoryTree = compact('categories','allCategories');

        return view('admin.modules.blog.import.create', [
            'modelParams' => $modelParams,
            'import' => $import,
            'categoryTree' => $categoryTree,
            'telegramBots' => TelegramBot::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Import $import)
    {
        if (Import::updatePost($import, $request->post())) {
            return redirect(route('admin.blog.import.index'));
        }

        return redirect(route('admin.blog.import.edit', ['post' => $import]));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Import $import)
    {
        if (!$import->delete()) {
            session()->flash('message_warning', 'There are descendants, you need to delete them first');
        }

        return redirect(route('admin.blog.import.index'));
    }
}
