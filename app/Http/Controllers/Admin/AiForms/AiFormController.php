<?php

namespace App\Http\Controllers\Admin\AiForms;

use App\Http\Controllers\Controller;
use App\Models\Modules\AiForm\AiForm;
use App\Models\Modules\Blog\Category;
use Illuminate\Http\Request;

class AiFormController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param Request $request
     */
    public function index(Request $request)
    {
        $aiFormsConfig = AiForm::query()->orderBy('id', 'desc')->get();

        return view('admin.modules.aiform.index', ['aiFormsConfig' => $aiFormsConfig]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('parent_id', '=', null)->get();
        $allCategories = Category::pluck('title','id')->all();

        return view('admin.modules.aiform.edit', compact('categories', 'allCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (is_string($result = AiForm::createOrUpdate($request->post()))) {
            session()->flash('message_warning', $result);
        }

        return redirect(route('admin.module.ai-form.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AiForm $aiForm)
    {
        $categories = Category::where('parent_id', '=', null)->get();
        $allCategories = Category::pluck('title','id')->all();

        return view('admin.modules.aiform.edit', compact('aiForm', 'categories', 'allCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AiForm $aiForm)
    {
        if (is_string($result = $aiForm::createOrUpdate($request->post(), $aiForm))) {
            session()->flash('message_warning', $result);
        }

        return redirect(route('admin.module.ai-form.index', $aiForm));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AiForm $aiForm)
    {
        if (!$aiForm->delete()) {
            session()->flash('message_warning', 'Delete error');
        }

        return redirect(route('admin.module.ai-form.index'));
    }
}
