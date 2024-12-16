<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Services\ArticleService;
use Illuminate\Http\Request;

class ArticleController extends Controller
{

    protected $articleService;

    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
    }
    /**
     * @OA\Get(
     *      path="/articles",
     *      summary="List all articles",
     *      description="Retrieve all articles with pagination",
     *      tags={"Articles"},
     *      @OA\Response(response=200, description="List of articles retrieved successfully")
     * )
     */

    public function index(Request $request)
    {
        $articles = Article::query();

        $articles = $this->paginateQuery($articles, $request);

        return $this->fetchSuccess(ArticleResource::collection($articles));
    }

    /**
     * @OA\Get(
     *      path="/articles/search",
     *      summary="Search articles",
     *      description="Search for articles using filters like keyword, date, category, and source",
     *      tags={"Articles"},
     *      @OA\Parameter(name="keyword", in="query", description="Search keyword", required=false),
     *      @OA\Parameter(name="date", in="query", description="Date filter (YYYY-MM-DD)", required=false),
     *      @OA\Parameter(name="category", in="query", description="Category filter", required=false),
     *      @OA\Parameter(name="source", in="query", description="Source filter", required=false),
     *      @OA\Response(response=200, description="Filtered articles list retrieved")
     * )
     */
    public function search(Request $request)
    {
        $filters = $request->only(['keyword', 'date', 'category', 'source']);
        $articles = $this->articleService->searchArticles($filters);

        return $this->fetchSuccess(ArticleResource::collection($articles));
    }

    /**
     * @OA\Get(
     *      path="/articles/{uuid}",
     *      summary="Get article by UUID",
     *      description="Retrieve a specific article by its UUID",
     *      tags={"Articles"},
     *      @OA\Parameter(
     *          name="uuid",
     *          in="path",
     *          required=true,
     *          description="UUID of the article",
     *          @OA\Schema(type="string", example="123e4567-e89b-12d3-a456-426614174000")
     *      ),
     *      @OA\Response(response=200, description="Article retrieved successfully"),
     *      @OA\Response(response=404, description="Article not found")
     * )
     */
    public function show($uuid)
    {
        $article = Article::where('uuid', $uuid)->firstOrFail();

        return $this->fetchSuccess(new ArticleResource($article));
    }
}
