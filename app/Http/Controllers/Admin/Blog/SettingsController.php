<?php

namespace App\Http\Controllers\Admin\Blog;

use App\Http\Controllers\Controller;
use App\Models\TelegramBot;
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
        return view('admin.modules.blog.settings.index', [
            'settings' => $settingBlog,
            'telegramBots' => TelegramBot::all(),
        ]);
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
            'telegram_bot' => 'sometimes|nullable|string',
        ]);

        $settingBlog->api_secret_key_rss_export = $request->get('api_secret_key_rss_export');
        $settingBlog->telegram_bot = $request->get('telegram_bot') ?? "";
        $settingBlog->save();

        return redirect(route('admin.blog.settings.index'));

    }

}
