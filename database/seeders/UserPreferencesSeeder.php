<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserPreference;
use App\Models\UserPreferenceAuthor;
use App\Models\UserPreferenceCategory;
use App\Models\UserPreferenceSource;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class UserPreferencesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserPreferenceSource::factory()->count(20)->create();

        UserPreferenceCategory::factory()->count(20)->create();

        UserPreferenceAuthor::factory()->count(20)->create();
    }
}
