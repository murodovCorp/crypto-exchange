<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;

trait HasLog
{

    public function error(\Throwable $e)
    {
        Log::error($e->getMessage(), [
            'code'      => $e->getCode(),
            'line'      => $e->getLine(),
            'trace'     => $e->getTrace(),
            'previous'  => $e->getPrevious(),
            'file'      => $e->getFile(),
        ]);
    }

}
