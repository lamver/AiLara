<?php

namespace App\Http\Controllers\Admin\Blog;

use App\Http\Controllers\Controller;
use App\Settings\SettingBlog;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SettingsController extends Controller
{


    /**
     * @param SettingBlog $settingBlog
     * @return View
     */
    public function index(SettingBlog $settingBlog): View
    {
        return view('admin.modules.blog.settings.index', ['apiSecretKeyRssExport' => $settingBlog->api_secret_key_rss_export]);
    }

    /**
     * @param Request $request
     * @param SettingBlog $settingBlog
     * @return RedirectResponse
     */
    public function update(Request $request, SettingBlog $settingBlog): RedirectResponse
    {

        $request->validate([
            'api_secret_key_rss_export' => 'required|string|min:10',
        ]);

        $settingBlog->api_secret_key_rss_export = $request->get('api_secret_key_rss_export');
        $settingBlog->save();

        return redirect(route('admin.blog.settings.index'));

    }

}
