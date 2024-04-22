<?php


namespace App\Http\Controllers\Admin;


use App\Models\AiLaraConfig;
use App\Settings\SettingGeneral;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Artisan;

class MainController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;


    public function index(Request $request)
    {
        return view('admin.index', ['slot' => 'dd']);
    }

    public function __invoke(SettingGeneral $settings){
        return view('index', [
            'site_name' => $settings->site_name,
        ]);
    }

    public function configuration(Request $request, SettingGeneral $settings)
    {
        //dd($settings->toArray());
        //$settings = new SettingGeneral();

/*        dd($settings->toArray());

        app(SettingGeneral::class)->site_name;*/
        //echo $settings->site_name;

        Artisan::call('cache:clear');
        $aiLaraConfig = new AiLaraConfig();

        if (!empty($request->post())) {
            $aiLaraConfig->save($request->post());
            sleep(5);
            return redirect(route('admin.configuration'));
        }

        $config = $aiLaraConfig->getAll();

        return view('admin.configuration', ['config' => $config]);
    }

    public function robotsTxt(Request $request)
    {
        $robotsTxtPath = base_path().'/public/robots.txt';

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
