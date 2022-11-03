<?php

namespace App\Http\Controllers\Client;

use App\Models\Transaction;
use App\Models\User;
use App\Services\Client\MainService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Inertia\Inertia;
use Inertia\Response;
use JetBrains\PhpStorm\ArrayShape;

class MainController extends BaseController
{

    public function __construct(private MainService $mainService, User $arg){}

    public function index(): Response
    {
        $data = $this->mainService->index();

        return Inertia::render('Client/Main', $data);
    }
    /**
     * Изменение статуса
     *
     * @group Транзакции
     *
     * @param Request $request
     * @return Application|Factory|View
     */
    public function process(Request $request): Application|Factory|View
    {
        return $this->mainService->process($request->all(), $request->session()->getId()); // $this->asset($assetId)
    }

    public function processSave($id, Request $request): Application|Factory|View
    {
        return $this->mainService->processSave($id, $request->all(), $request->session()->getId()); // $this->asset($assetId)
    }

    #[ArrayShape(['status' => "array|mixed"])]
    public function status(mixed $id): array
    {
        /** @var Transaction $transaction */
        $transaction = Transaction::query()->find($id);

        return [
            'status' => data_get(Transaction::STATUSES, $transaction->status, 'Ошибка! Свяжитесь с оператором')
        ];
    }

}
