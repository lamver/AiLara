<?php


namespace App\Http\Controllers\Admin;


use App\Services\AiSearchApi;
use App\Settings\SettingGeneral;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\View\View;

/**
 * Class MainController
 *
 * @package App\Http\Controllers\Admin
 */
class MainController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function index(Request $request)
    {
        try {
            $aisUserData = (new AiSearchApi())->getUserData();
        } catch (\Exception $e) {
            $aisUserData = $e->getMessage();
        }

        return view('admin.index', [
            'slot' => 'dd',
            'aisUserData' => $aisUserData,
        ]);
    }

    public function __invoke(SettingGeneral $settings)
    {
        return view('index', [
            'site_name' => $settings->site_name,
        ]);
    }

    /**
     * Clears the cache and saves the configuration settings.
     *
     * @param Request $request
     * @param SettingGeneral $settings
     * @return RedirectResponse|View
     */
    public function configuration(Request $request, SettingGeneral $settings)
    {
        Artisan::call('cache:clear');

        if (!empty($request->post())) {
            $settings->prepareAndSave($request->post(), $settings);
            return redirect(route('admin.configuration'));
        }

        $configArray = $settings->toArray();
        ksort($configArray);

        return view('admin.configuration', ['config' => $configArray]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function robotsTxt(Request $request)
    {
        $robotsTxtPath = base_path() . '/public/robots.txt';

        if (!file_exists($robotsTxtPath)) {
            $content = 'User-agent: *' . PHP_EOL;
            $content .= 'Disallow: /' . PHP_EOL;

            file_put_contents($robotsTxtPath, $content);
        }

        if ($request->post('robotsTxtContent') !== null) {
            file_put_contents($robotsTxtPath, $request->post('robotsTxtContent'));
            redirect(route('admin.configuration.robots_txt'));
        }

        $robotsTxtContent = file_get_contents($robotsTxtPath);

        return view('admin.robots-txt', ['robotsTxtContent' => $robotsTxtContent]);
    }

}
