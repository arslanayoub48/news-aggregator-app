<?php

namespace App\Models;

use App\Traits\Relationships\Common\User\BelongsTo as UserBelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserPreferenceAuthor extends Model
{
    use SoftDeletes;
    use HasFactory;
    use UserBelongsTo;

    protected $guarded = [];


}
