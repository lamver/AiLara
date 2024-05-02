<?php

namespace App\Http\Controllers\Modules\Task;

use App\Http\Controllers\Controller;
use App\Models\Modules\AiForm\AiForm;
use App\Models\Tasks;
use App\Services\Modules\Module;
use Illuminate\Http\Request;

/**
 * Class TaskController
 *
 * @package App\Http\Controllers\Modules\Task
 */
class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function index(Request $request)
    {
        $aiForm = AiForm::loadDefaultForm();

        return view('modules.ai-form.index', ['aiForm' => $aiForm]);
    }

    public function viewAiFormPage(Request $request)
    {
        $slugForm = str_replace(Module::getWebRoutePrefix(Module::MODULE_AI_FORM) .'/', "",  '/'.$request->path());

        if (empty($aiForm = AiForm::query()->where(['slug' => $slugForm])->first())) {
            return abort(404);
        }

        return view('modules.ai-form.index', ['aiForm' => $aiForm]);
    }

    public function viewResultTask(Request $request, $slug, $id)
    {
        dd('viewResultTask');
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
    public function show(Tasks $tasks)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tasks $tasks)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tasks $tasks)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tasks $tasks)
    {
        //
    }
}
