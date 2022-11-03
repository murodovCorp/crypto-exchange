<?php

namespace App\Http\Controllers\Admin;

use App\Services\Admin\TransactionService;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Inertia\Inertia;
use Inertia\Response;

class TransactionController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct(private TransactionService $transactionService){}

    /**
     * Список транзакций
     * Получение списка транзакций с пагинацией.
     *
     * @urlParam status
     * @urlParam type
     * @urlParam promo_code
     * @urlParam user_id
     *
     * @group Транзакции
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $data = $this->transactionService->index($request->all());

        return Inertia::render('Admin/Transactions', $data);
    }

    /**
     * Изменение статуса транзакции
     *
     * @group Транзакции
     *
     * @param mixed $id
     * @param Request $request
     * @return JsonResponse
     * @throws GuzzleException
     */
    public function statusChange(mixed $id, Request $request): JsonResponse
    {
        return $this->transactionService->statusChange($id, $request->all());
    }

}
