<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Relationships\Common\User\BelongsTo as UserBelongsTo;


class UserPreferenceSource extends Model
{
    use SoftDeletes;
    use HasFactory;
    use UserBelongsTo;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
