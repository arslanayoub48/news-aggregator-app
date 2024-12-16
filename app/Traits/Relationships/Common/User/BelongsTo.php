<?php

namespace App\Traits\Relationships\Common\User;

use App\Models\User;


trait BelongsTo
{

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
