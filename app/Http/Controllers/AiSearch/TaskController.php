<?php


namespace App\Http\Controllers\AiSearch;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;

class TaskController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function view(Request $request, $slug, $id)
    {
        return view('aisearch.task.view', [
            'slot' => 'dd',
            'id'   => $id,
            'slug' => $slug,
        ]);
    }

}
