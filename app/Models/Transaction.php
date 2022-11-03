<?php

namespace App\Models;

use App\Traits\HasUser;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property string $session_id
 * @property string $uuid
 * @property string $promo_code
 * @property string $status
 * @property string $type
 * @property array $data
 * @property User|BelongsTo $user
 */

class Transaction extends Model
{
    use HasUser, Searchable;

    protected $guarded = ['id'];

    const STATUS_CREATE = 'create';
    const STATUS_IN_PROGRESS = 'inProgress';
    const STATUS_ERROR = 'error';
    const STATUS_READY = 'ready';

    public const STATUSES = [
        self::STATUS_CREATE => 'Создан',
        self::STATUS_IN_PROGRESS => 'В процессе',
        self::STATUS_ERROR => 'Ошибка',
        self::STATUS_READY => 'Готово',
    ];

    protected $casts = [
        'user_id'       => 'integer',
        'session_id'    => 'string',
        'uuid'          => 'string',
        'promo_code'    => 'string',
        'status'        => 'string',
        'type'          => 'string',
        'data'          => 'array',
    ];

    /** Relations */
    public function session(): BelongsTo
    {
        return $this->belongsTo(Session::class);
    }

}
