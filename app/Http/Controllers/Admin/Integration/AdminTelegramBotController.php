<?php

namespace App\Http\Controllers\Admin\Integration;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTelegramBotRequest;
use App\Models\Modules\AiForm\AiForm;
use App\Models\TelegramBot;
use Illuminate\Contracts\Foundation\Application as ContractsApplication;
use Illuminate\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View as ContractsView;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Request;

class AdminTelegramBotController extends Controller
{
    public int $numberPaginate = 15;

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return Application|Factory|ContractsView|ContractsApplication|View
     */
    public function index(Request $request)
    {
        $bots = TelegramBot::orderBy('id', 'desc')->paginate($this->numberPaginate);

        return view('admin.integration.telegramBots.index', ['bots' => $bots]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.integration.telegramBots.create', [
            'forms' => AiForm::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param StoreTelegramBotRequest $request
     * @return RedirectResponse
     */
    public function store(StoreTelegramBotRequest $request): RedirectResponse
    {
        $bot = new TelegramBot();
        $bot['name'] = $request->name;
        $bot['token'] = $request->token;
        $bot['form_id'] = $request->form_id;
        $bot->save();

        $this->registerWebhook($bot);

        return redirect()->route('telegram-bots.index');
    }

    /**
     * Show the form for editing the specified resource.
     * @param $id
     * @return ContractsApplication|Factory|ContractsView|Application|View
     */
    public function edit($id)
    {
        return view('admin.integration.telegramBots.edit', [
            'bot' => TelegramBot::find((int)$id),
            'forms' => AiForm::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $bot = TelegramBot::find((int)$id);
        $bot->name = $request->name;
        $bot->token = $request->token;
        $bot->form_id = $request->form_id;
        $bot->save();

        $this->registerWebhook($bot);

        return redirect()->route('telegram-bots.index');
    }

    /**
     * Remove the specified resource from storage.
     * @param $id
     * @return RedirectResponse
     */
    public function destroy($id): RedirectResponse
    {
        $bot = TelegramBot::find((int)$id);
        $bot->delete();

        return redirect()->route('telegram-bots.index');
    }

    /**
     * @param $bot
     * @return mixed
     */
    public function registerWebhook($bot)
    {
        return $bot->registerWebhook()->send();
    }

}
