<?php
declare(strict_types=1);

namespace App\Services\Admin;

use App\Models\User;
use App\Traits\HasLog;
use Inertia\Inertia;
use Inertia\Response;

class UserService {

    use HasLog;

    public function index(array $filter): Response
    {

        $users = User::query()
            ->with(['transactions']);

        if (data_get($filter, 'name')) {
            $users->where('name', 'like', '%' . data_get($filter, 'name') . '%');
        }

        if (data_get($filter, 'email')) {
            $users->where('email', 'like', '%' . data_get($filter, 'email') . '%');
        }

        return Inertia::render('Admin/Users', [
            'users' => $users->paginate(10),
        ]);
    }

}
