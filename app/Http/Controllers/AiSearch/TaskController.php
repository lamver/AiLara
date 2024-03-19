<?php


namespace App\Http\Controllers\AiSearch;

use App\Services\AiSearchApi;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class TaskController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function view(AiSearchApi $aiSearchApi, $slug, $id)
    {
        return view('aisearch.task.view', [
            'id'   => $id,
            'slug' => $slug,
            'task' => $aiSearchApi->getTaskByTaskId($id),
        ]);
    }

}
