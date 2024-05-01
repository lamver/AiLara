<?php

namespace App\Http\Controllers\Admin\AiForms;

use App\Http\Controllers\Controller;
use App\Models\Modules\AiForm\AiForm;
use Illuminate\Http\Request;

class AiFormController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $aiFormsConfig = AiForm::query()->orderBy('id', 'desc')->get();

        return view('admin.modules.aiform.index', ['aiFormsConfig' => $aiFormsConfig]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.modules.aiform.edit');
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
        return view('admin.modules.aiform.edit', ['aiForm' => $aiForm]);
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
