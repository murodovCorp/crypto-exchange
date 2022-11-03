<?php

namespace App\Http\Controllers\Admin;

use App\Services\Admin\UserService;
use Elastic\Elasticsearch\ClientBuilder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Inertia\Response;

class UserController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct(private UserService $userService){}

    /**
     * Список пользователей
     * Получение списка пользователей с пагинацией.
     *
     * @urlParam status
     * @urlParam type
     * @urlParam promo_code
     * @urlParam user_id
     *
     * @group Пользователи
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $client = ClientBuilder::create()
            ->setElasticCloudId('laravel:dXMtY2VudHJhbDEuZ2NwLmNsb3VkLmVzLmlvJDgxODdjMDNhMTQ2ZjQxODA5MmQ2NmQ5OTRhYTNiNTAzJGQyN2I5ZTJmMDU4OTQ5ZDU5MjE5MDYyOTQzMmNmMjFi')
            ->setBasicAuthentication('<username>', '<password>')
            ->build();
        dd($client);
        return $this->userService->index($request->all());
    }

}
