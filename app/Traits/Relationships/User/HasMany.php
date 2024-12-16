<?php

namespace App\Traits\Relationships\User;

use App\Models\UserPreferenceAuthor;
use App\Models\UserPreferenceCategory;
use App\Models\UserPreferenceSource;

trait HasMany
{

    public function authors()
    {
        return $this->hasMany(UserPreferenceAuthor::class);
    }

    public function categories()
    {
        return $this->hasMany(UserPreferenceCategory::class);
    }

    public function sources()
    {
        return $this->hasMany(UserPreferenceSource::class);
    }
}
