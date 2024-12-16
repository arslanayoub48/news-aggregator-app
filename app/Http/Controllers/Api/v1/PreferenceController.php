<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserPreference\StoreRequest;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\UserPreferenceAuthorResource;
use App\Http\Resources\UserPreferenceCategoryResource;
use App\Http\Resources\UserPreferenceSourceResource;
use App\Services\PreferenceService;

use Illuminate\Http\Request;

class PreferenceController extends Controller
{
    protected $userPreferenceService;

    public function __construct(PreferenceService $userPreferenceService)
    {
        $this->userPreferenceService = $userPreferenceService;
    }

    /**
     * @OA\Post(
     *      path="/user/preferences",
     *      summary="Set user preferences",
     *      description="Save user preferences for sources, categories, and authors",
     *      tags={"User Preferences"},
     *      security={{ "sanctum": {} }},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(property="sources", type="array", @OA\Items(type="string", example="TechCrunch")),
     *              @OA\Property(property="categories", type="array", @OA\Items(type="string", example="Technology")),
     *              @OA\Property(property="authors", type="array", @OA\Items(type="string", example="John Doe"))
     *          )
     *      ),
     *      @OA\Response(response=200, description="Preferences saved successfully")
     * )
     */
    public function setPreferences(StoreRequest $request)
    {
        $preferences = $request->validated();

        $this->userPreferenceService->setUserPreferences($preferences);

        return $this->singleSuccessResponse('User preferences set successfully.');
    }

    /**
     * @OA\Get(
     *      path="/user/preferences",
     *      summary="Get user preferences",
     *      description="Retrieve user preferences for sources, categories, and authors",
     *      tags={"User Preferences"},
     *      security={{ "sanctum": {} }},
     *      @OA\Response(response=200, description="User preferences retrieved successfully"),
     *      @OA\Response(response=404, description="Preferences not found")
     * )
     */

    public function getPreferences()
    {
        $preferences = $this->userPreferenceService->getUserPreferences();

        if (!$preferences) {
            return $this->failure('No preferences found.');
        }

        return $this->fetchSuccess([
            'sources' => UserPreferenceSourceResource::collection($preferences['sources']),
            'categories' => UserPreferenceCategoryResource::collection($preferences['categories']),
            'authors' => UserPreferenceAuthorResource::collection($preferences['authors']),
        ]);
    }


    /**
     * @OA\Get(
     *      path="/user/personalized-feed",
     *      summary="Get personalized feed",
     *      description="Retrieve personalized feed based on user preferences",
     *      tags={"User Preferences"},
     *      security={{ "sanctum": {} }},
     *      @OA\Response(response=200, description="Personalized feed retrieved successfully")
     * )
     */
    public function getPersonalizedFeed()
    {
        $articles = $this->userPreferenceService->getPersonalizedFeed();

        return $this->fetchSuccess(ArticleResource::collection($articles));
    }
}
