<?php


namespace App\Http\Controllers\AiSearch\ControlPanel;

use App\Helpers\DomainHelper;
use App\Http\Controllers\Controller;
use App\Models\Pages as SeoPagesModel;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

/**
 * Class SeoPages
 *
 * @package App\Http\Controllers\AiSearch\ControlPanel
 */
class SeoPages extends Controller
{

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return Application|Factory|View
     */
    public function seoPagesList(Request $request)
    {
        $routes = collect(Route::getRoutes())->filter(function($route) {
            return in_array('GET', $route->methods());
        })->map(function($route) {
            $existingPage = SeoPagesModel::where('uri', $route->uri())->first();

            if (!$existingPage) {
                SeoPagesModel::insert([
                    'uri' => $route->uri(),
                ]);
            }

        })->toArray();

        $allPages = SeoPagesModel::all();

        return view('admin.integration.seoPagesList', [
            'allPages'  => $allPages,
            'routes'    => $routes,
        ]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param $id
     *
     * @return Application|Factory|View
     */
    public function seoPageEdit(Request $request, $id)
    {
        $existingPage = SeoPagesModel::where('id', $id)->first();

        if (!$existingPage) {
            redirect('seoPagesList');
        }

        return view('admin.integration.seoEditPage', [
            'existingPage'  => $existingPage,
        ]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param $id
     *
     * @return Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function seoPageSave(Request $request, $id)
    {
        $aiSearchSeoPage = SeoPagesModel::find($id);

        // Обновляем поля
        $aiSearchSeoPage->meta_title = $request->post('meta_title');
        $aiSearchSeoPage->meta_description = $request->post('meta_description');
        $aiSearchSeoPage->meta_keywords = $request->post('meta_keywords');
        $aiSearchSeoPage->meta_image_path = $request->post('meta_image_path');
        $aiSearchSeoPage->preview_title = $request->post('preview_title');
        $aiSearchSeoPage->preview_description = $request->post('preview_description');
        $aiSearchSeoPage->preview_image_path = $request->post('preview_image_path');
        $aiSearchSeoPage->preview_icon_svg_code = $request->post('preview_icon_svg_code');
        $aiSearchSeoPage->seo_title = $request->post('seo_title');
        $aiSearchSeoPage->seo_description = $request->post('seo_description');
        $aiSearchSeoPage->seo_content_page = $request->post('seo_content_page');
        try {
            // Сохраняем изменения
            $aiSearchSeoPage->save();
            SeoPagesModel::updateCachePage($aiSearchSeoPage->uri);
            redirect(\route('pagesEdit', ['id' => $id]));
        } catch (\Exception $e) {
            Session::flash('flash_message_type', 'warning');
            Session::flash('flash message', 'Выброшено исключение: '.  $e->getMessage(). "\n");
        }

        return redirect(\route('admin.ais.page.edit', ['id' => $id]));
    }

}
