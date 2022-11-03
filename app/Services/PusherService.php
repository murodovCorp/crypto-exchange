<?php
declare(strict_types=1);

namespace App\Services;

use App\Traits\HasLog;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Pusher\Pusher;
use Pusher\PusherException;

class PusherService {

    use HasLog;

    public Pusher $pusher;

    /**
     * @throws PusherException
     * @throws Exception
     */
    public function __construct($authKey, $secret, $appId, $options) {

        try {

            $this->pusher = new Pusher($authKey, $secret, $appId, $options);

        } catch (Exception $e) {

            $this->error($e);
            throw new Exception('Что-то пошло не так свяжитесь с разработчиком');

        }
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function trigger(array|string $channels, string $event, array $data): object
    {
        try {

            return $this->pusher->trigger($channels, $event, $data);

        } catch (Exception $e) {

            $this->error($e);
            throw new Exception('Что-то пошло не так свяжитесь с разработчиком');

        }

    }
}
