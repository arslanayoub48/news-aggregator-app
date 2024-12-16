<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\UserPreferenceSource;
use App\Models\UserPreferenceCategory;
use App\Models\UserPreferenceAuthor;
use App\Models\Article;
use Illuminate\Support\Arr;

class PreferenceService
{
    /**
     * Set user preferences.
     *
     * @param  array  $preferences
     * @return void
     */
    public function setUserPreferences(array $preferences)
    {
        $userId = getLoggedInUserId();

        DB::transaction(function () use ($userId, $preferences) {
            // Delete old preferences
            UserPreferenceSource::where('user_id', $userId)->delete();
            UserPreferenceCategory::where('user_id', $userId)->delete();
            UserPreferenceAuthor::where('user_id', $userId)->delete();

            // Insert sources
            if (!empty($preferences['sources'])) {
                foreach ($preferences['sources'] as $source) {
                    UserPreferenceSource::create([
                        'uuid' => Str::uuid(),
                        'user_id' => $userId,
                        'source' => $source,
                    ]);
                }
            }

            // Insert categories
            if (!empty($preferences['categories'])) {
                foreach ($preferences['categories'] as $category) {
                    UserPreferenceCategory::create([
                        'uuid' => Str::uuid(),
                        'user_id' => $userId,
                        'category' => $category,
                    ]);
                }
            }

            // Insert authors
            if (!empty($preferences['authors'])) {
                foreach ($preferences['authors'] as $author) {
                    UserPreferenceAuthor::create([
                        'uuid' => Str::uuid(),
                        'user_id' => $userId,
                        'author' => $author,
                    ]);
                }
            }
        });
    }

    /**
     * Get user preferences.
     *
     * @return array
     */
    public function getUserPreferences()
    {
        $userId = getLoggedInUserId();

        return [
            'sources' => UserPreferenceSource::where('user_id', $userId)->get(), // Get full model instances
            'categories' => UserPreferenceCategory::where('user_id', $userId)->get(), // Get full model instances
            'authors' => UserPreferenceAuthor::where('user_id', $userId)->get(), // Get full model instances
        ];
    }

    /**
     * Get personalized feed based on user preferences.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getPersonalizedFeed()
    {
        $preferences = $this->getUserPreferences();

        $query = Article::query();

        if (!empty($preferences['sources'])) {
            $query->whereIn('source', Arr::flatten($preferences['sources']));
        }

        if (!empty($preferences['categories'])) {
            $query->whereIn('category', Arr::flatten($preferences['categories']));
        }

        if (!empty($preferences['authors'])) {
            $query->whereIn('author', Arr::flatten($preferences['authors']));
        }

        return $query->get();
    }
}
