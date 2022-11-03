<?php
declare(strict_types=1);

namespace App\Services\Client;

use App\Models\Session;
use App\Models\Transaction;
use App\Traits\HasLog;
use Error;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Response as FacadesResponse;
use Inertia\Inertia;
use Inertia\Response;
use Ramsey\Uuid\Uuid;

class MainService {

    use HasLog;

    public array $headers = [
        'X-CoinAPI-Key' => '196E4485-DFD9-4A18-936C-97D9725F9617',
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
    ];

    public function index(): array
    {
        // $this->asset('BTC;ETH;USDT')
        return  [
            'cryptocurrencies' => [
                [
                    "asset_id" => "BTC",
                    "name" => "Bitcoin",
                    "type_is_crypto" => 1,
                    "data_quote_start" => "2014-02-24T17:43:05.0000000Z",
                    "data_quote_end" => "2022-10-08T00:00:00.0000000Z",
                    "data_orderbook_start" => "2014-02-24T17:43:05.0000000Z",
                    "data_orderbook_end" => "2022-10-08T00:00:00.0000000Z",
                    "data_trade_start" => "2010-07-17T23:09:17.0000000Z",
                    "data_trade_end" => "2022-10-08T00:00:00.0000000Z",
                    "data_symbols_count" => 117193,
                    "volume_1hrs_usd" => 6.9883306016761E+19,
                    "volume_1day_usd" => 2.0964994018456E+20,
                    "volume_1mth_usd" => 1.9956789266182E+21,
                    "price_usd" => 19358.826894032,
                    "id_icon" => "4caf2b16-a017-4e26-a348-2cea69c34cba",
                    "data_start" => "2010-07-17",
                    "data_end" => "2022-10-08",
                ],
                [
                    "asset_id" => "ETH",
                    "name" => "Ethereum",
                    "type_is_crypto" => 1,
                    "data_quote_start" => "2014-02-24T17:43:05.0000000Z",
                    "data_quote_end" => "2022-10-08T00:00:00.0000000Z",
                    "data_orderbook_start" => "2014-02-24T17:43:05.0000000Z",
                    "data_orderbook_end" => "2022-10-08T00:00:00.0000000Z",
                    "data_trade_start" => "2010-07-17T23:09:17.0000000Z",
                    "data_trade_end" => "2022-10-08T00:00:00.0000000Z",
                    "data_symbols_count" => 217193,
                    "volume_1hrs_usd" => 6.9883306016761E+19,
                    "volume_1day_usd" => 2.0964994018456E+20,
                    "volume_1mth_usd" => 1.9956789266182E+21,
                    "price_usd" => 1158.826894032,
                    "id_icon" => "4caf2b16-a017-4e26-a348-2cea69c34cba",
                    "data_start" => "2010-07-17",
                    "data_end" => "2022-10-08",
                ],
            ],
            'currencies' => [
                [
                    "asset_id" => "UZS",
                    "imgUrl" => "UzbekistanFlag.png",
                    "name" => "Узбекский сум",
                    "price_usd" => 0.000090,
                    "type_is_crypto" => 0,
                ],
            ]
//            'icons' => $this->icons('BTC')
        ];
    }

    public function process(array $data, ?string $sessionId): Application|Factory|View
    {

        /** @var Session $session */
        $session = Session::query()->find($sessionId);

        $transaction = Transaction::query()->firstOrCreate([
            'user_id' => $session->user_id,
            'session_id' => $session->id,
            'status' => Transaction::STATUS_CREATE,
            'type' => data_get($data, 'type'),
        ], [
            'uuid' => (string)Uuid::uuid4(),
            'user_id' => $session->user_id,
            'session_id' => $session->id,
            'status' => 'create',
            'type' => data_get($data, 'type'),
            'data' => [
                'amount' => data_get($data, 'amount'),
                'asset' => data_get($data, 'asset'),
            ]
        ]);

        return view('process-exchange', [
            'asset' => [
                "asset_id" => "BTC",
                "name" => "Bitcoin",
                "type_is_crypto" => 1,
                "data_quote_start" => "2014-02-24T17:43:05.0000000Z",
                "data_quote_end" => "2022-10-08T00:00:00.0000000Z",
                "data_orderbook_start" => "2014-02-24T17:43:05.0000000Z",
                "data_orderbook_end" => "2022-10-08T00:00:00.0000000Z",
                "data_trade_start" => "2010-07-17T23:09:17.0000000Z",
                "data_trade_end" => "2022-10-08T00:00:00.0000000Z",
                "data_symbols_count" => 117193,
                "volume_1hrs_usd" => 6.9883306016761E+19,
                "volume_1day_usd" => 2.0964994018456E+20,
                "volume_1mth_usd" => 1.9956789266182E+21,
                "price_usd" => 19358.826894032,
                "id_icon" => "4caf2b16-a017-4e26-a348-2cea69c34cba",
                "data_start" => "2010-07-17",
                "data_end" => "2022-10-08",
            ],
            'transaction' => $transaction
        ]); // $this->asset($assetId)
    }

    public function processSave($id, array $data, ?string $sessionId): Application|Factory|View
    {

        $transaction = Transaction::query()
            ->where('session_id', $sessionId)
            ->whereIn('status', [Transaction::STATUS_CREATE, Transaction::STATUS_IN_PROGRESS])
            ->orWhere('user_id', auth()->user()?->id)
            ->orWhereIn('status', [Transaction::STATUS_CREATE, Transaction::STATUS_IN_PROGRESS])
            ->find($id);

        if (!data_get($transaction, 'id')) {
            throw new Error('Транзакция не найдена', 422);
        }

        $prepareData = [
            'address' => data_get($data, 'address'),
            'name' => data_get($data, 'name'),
            'surname' => data_get($data, 'surname'),
            'email' => data_get($data, 'email'),
            'amount' => data_get($transaction, 'data.amount'),
            'asset' => data_get($transaction, 'data.asset'),
        ];

        $transaction->update([
            'promo_code' => data_get($transaction, 'promo_code'),
            'status' => Transaction::STATUS_IN_PROGRESS,
            'data' => $prepareData,
        ]);

        return view('process-exchange', [
            'asset' => [
                "asset_id" => "BTC",
                "name" => "Bitcoin",
                "type_is_crypto" => 1,
                "data_quote_start" => "2014-02-24T17:43:05.0000000Z",
                "data_quote_end" => "2022-10-08T00:00:00.0000000Z",
                "data_orderbook_start" => "2014-02-24T17:43:05.0000000Z",
                "data_orderbook_end" => "2022-10-08T00:00:00.0000000Z",
                "data_trade_start" => "2010-07-17T23:09:17.0000000Z",
                "data_trade_end" => "2022-10-08T00:00:00.0000000Z",
                "data_symbols_count" => 117193,
                "volume_1hrs_usd" => 6.9883306016761E+19,
                "volume_1day_usd" => 2.0964994018456E+20,
                "volume_1mth_usd" => 1.9956789266182E+21,
                "price_usd" => 19358.826894032,
                "id_icon" => "4caf2b16-a017-4e26-a348-2cea69c34cba",
                "data_start" => "2010-07-17",
                "data_end" => "2022-10-08",
            ],
            'transaction' => $transaction
        ]);
    }

    public function exchanges(): array
    {
        $request = Http::withHeaders($this->headers)->get('https://rest.coinapi.io/v1/exchanges');

        return $request->json();
    }

    public function exchange(mixed $exchangeId): array
    {
        $request = Http::withHeaders($this->headers)->get('https://rest.coinapi.io/v1/exchanges', [
            'filter_exchange_id' => $exchangeId
        ]);

        return $request->json();
    }

    public function assets(): array
    {
        $request = Http::withHeaders($this->headers)->get('https://rest.coinapi.io/v1/assets');

        return $request->json();
    }

    public function asset(mixed $assetId): array
    {
        $request = Http::withHeaders($this->headers)->get('https://rest.coinapi.io/v1/assets', [
            'filter_asset_id' => $assetId
        ]);

        return $request->json();
    }

    public function icons(mixed $assetId): array
    {
        $request = Http::withHeaders($this->headers)->get('https://rest.coinapi.io/v1/assets/icons/1', [
            'filter_asset_id' => $assetId
        ]);

        return $request->json();
    }

    public function symbols(mixed $filterSymbolId, mixed $filterExchangeId, mixed $filterAssetId): array
    {
        $request = Http::withHeaders($this->headers)->get('https://rest.coinapi.io/v1/symbols');

        return $request->json();
    }

    public function symbol(mixed $filterSymbolId, mixed $filterExchangeId, mixed $filterAssetId): array
    {
        $request = Http::withHeaders($this->headers)->get('https://rest.coinapi.io/v1/assets', [
            'filter_symbol_id' => $filterSymbolId,
            'filter_exchange_id' => $filterExchangeId,
            'filter_asset_id' => $filterAssetId
        ]);

        return $request->json();
    }

}
