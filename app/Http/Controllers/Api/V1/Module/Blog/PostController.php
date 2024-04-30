<?php

namespace App\Http\Controllers\Api\V1\Module\Blog;

use App\Http\Controllers\Api\V1\ApiBaseController;
use App\Http\Requests\LoadPostRequest;
use App\Models\Modules\Blog\Posts;
use Illuminate\Http\Request;

class PostController extends ApiBaseController
{
    public function load(LoadPostRequest $request)
    {
        $lastId = $request->get('lastId');
        $limit = $request->get('limit');

        $posts = Posts::loadPosts($lastId, $limit);

        return $this->success(
            [
                'lastId' => $lastId,
                'limit' => $limit,
                'posts' => $posts
            ]
        );
    }

}
