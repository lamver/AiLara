<?php


namespace App\Http\Controllers\Admin;


use App\Models\AiLaraConfig;
use App\Settings\SettingGeneral;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\View\View;

class MainController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;


    public function index(Request $request)
    {
        return view('admin.index', ['slot' => 'dd']);
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
            $this->configurationSave($request, $settings);
            return redirect(route('admin.configuration'));
        }

        return view('admin.configuration', ['config' => $settings->toArray()]);

    }

    /**
     * Saves the configuration settings.
     *
     * @param Request $request
     * @param SettingGeneral $settings
     * @return SettingGeneral
     */
    protected function configurationSave(Request $request, SettingGeneral $settings): SettingGeneral
    {
        $settings->site_name = $request->post('site_name') ?? "";
        $settings->site_active = (bool)$request->post('site_active');
        $settings->app_name = $request->post('app_name') ?? "";
        $settings->logo_path = $request->post('logo_path') ?? "";
        $settings->logo_title = $request->post('logo_title') ?? "";
        $settings->logo_height_px = $request->post('logo_height_px');
        $settings->logo_width_px = $request->post('logo_width_px');
        $settings->logo_width_px = $request->post('logo_width_px');
        $settings->counter_external_code = $request->post('counter_external_code') ?? "";
        $settings->test = $request->post('test');
        $settings->api_key_aisearch = $request->post('api_key_aisearch') ?? "";
        $settings->api_host = $request->post('api_host') ?? "";
        $settings->admin_prefix = $request->post('admin_prefix') ?? "";

        return $settings->save();

    }

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
