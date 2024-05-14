<?php

namespace App\Http\Controllers\Api\V1\Module\Blog;

use App\Http\Controllers\Api\V1\ApiBaseController;
use App\Models\Modules\Blog\Category;
use App\Models\Modules\Blog\Posts;
use App\Settings\SettingBlog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Feed\Feed;

class ExportController extends ApiBaseController
{
    const RSS_ITEMS_LENGTH = 100;
    public function export(Request $request, SettingBlog $settingBlog, $key)
    {
        if (!Auth::check() && strcmp($key, $settingBlog->api_secret_key_rss_export) !== 0) {
            return response()->json( $this->error('Unauthorized'), 401 );
        }

        $statusArray = [];

        if ($request->get('status')) {
            foreach (explode(',', $request->get('status')) as $status) {
                $statusArray[] = trim($status);
            }
        }

        if ($request->get('type') && $request->get('type') === "sql ") {
            return $this->success([ 'ddd']);
        }

        $post = Posts::whereIn('status', $statusArray)->limit(self::RSS_ITEMS_LENGTH)->get();

        return  $this->getRss($post)->toResponse($request);

    }

    /**
     * @param $post
     * @return Feed
     */
    public function getRss($post): Feed
    {
        return new Feed(
            "",
            $post,
            '',
            'feed::rss',
        );
    }

}
