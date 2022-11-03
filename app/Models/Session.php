<?php

namespace App\Models;

use App\Traits\HasTransaction;
use App\Traits\HasUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Session
 *
 * @property string $id
 * @property int $user_id
 * @property string $ip_address
 * @property string $user_agent
 * @property string $payload
 * @property int $last_activity
 * @property User|BelongsTo $user
 */

class Session extends Model
{
    use HasUser;
    use HasTransaction;

    protected $guarded = ['id'];

    protected $casts = [
        'id'                => 'string',
        'user_id'           => 'integer',
        'ip_address'        => 'string',
        'user_agent'        => 'string',
        'payload'           => 'string',
        'last_activity'     => 'integer',
    ];

}
