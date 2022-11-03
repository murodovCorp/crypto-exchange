<?php
declare(strict_types=1);

namespace App\Services\Admin;

use App\Models\Transaction;
use App\Services\PusherService;
use App\Traits\HasLog;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response as FacadesResponse;

class TransactionService {

    use HasLog;

    public function index(array $filter): array
    {

        $transactions = Transaction::query()
            ->with(['user', 'session']);

        if (data_get($filter, 'status')) {
            $transactions->where('status', data_get($filter, 'status'));
        }

        if (data_get($filter, 'type')) {
            $transactions->where('type', data_get($filter, 'type'));
        }

        if (data_get($filter, 'promo_code')) {
            $transactions->where('promo_code', data_get($filter, 'promo_code'));
        }

        if (data_get($filter, 'user_id')) {
            $transactions->where('user_id', data_get($filter, 'user_id'));
        }

        return [
            'transactions' => $transactions->paginate(10),
            'statuses' => Transaction::STATUSES
        ];
    }

    /**
     * @throws Exception
     * @throws GuzzleException
     */
    public function statusChange(mixed $id, array $data): JsonResponse
    {
        $transaction = Transaction::query()->find($id);
        $transactionData = $transaction->data;

        if (!data_get($data, 'status')) {
            throw new Exception('Не указан статус', 422);
        }

        if (data_get($data, 'status') === Transaction::STATUS_ERROR && !data_get($data, 'message')) {
            throw new Exception('Статус указан как ошибка. Необходимо отправить пользователю сообщение о причине ошибки', 422);
        }

        $transaction
            ->update([
                'status' => data_get($data, 'status'),
                'data' => data_set($transactionData, 'message', data_get($data, 'message'))
            ]);

        $pusher = new PusherService(config('app.auth_key'), config('app.secret'), config('app.app_id'), [
            'cluster' => config('app.cluster')
        ]);

        $pusher->trigger('transaction', "my.transaction.$id", compact('transaction'));

        return FacadesResponse::json(compact('transaction'));
    }

}
