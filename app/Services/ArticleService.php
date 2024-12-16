<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Article;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class ArticleService
{
    protected $guardianApiKey;
    protected $nytApiKey;
    protected $newsApis = [];
    protected $httpClient;

    public function __construct()
    {
        $this->guardianApiKey = env('GUARDIAN_API_KEY') ?? '';
        $this->nytApiKey = env('NYT_API_KEY') ?? '';

        $this->newsApis = [
            'newsapi' => 'https://newsapi.org/v2/top-headlines?' . http_build_query([
                    'apiKey' => env('NEWS_API_KEY'),
                    'language' => 'en',
                    'country' => 'us',
                ]),
            'guardian' => "https://content.guardianapis.com/search?api-key={$this->guardianApiKey}",
            'nyt' => "https://api.nytimes.com/svc/news/v3/content/all/all.json?api-key={$this->nytApiKey}",
        ];

        // Initialize a reusable HTTP client
        $this->httpClient = new Client();
    }

    private function generateSlug($title)
    {
        return Str::slug($title);
    }

    public function fetchAndStoreArticles()
    {
        $allArticles = [];

        foreach ($this->newsApis as $source => $url) {
            try {
                $articles = $this->fetchArticlesFromSource($source, $url);
                $allArticles = array_merge($allArticles, $articles);
            } catch (\Exception $e) {
                Log::error("Error fetching articles from {$source}: " . $e->getMessage());
            }
        }

        // Perform batch upsert for efficiency
        DB::transaction(function () use ($allArticles) {
            Article::upsert($allArticles, ['url'], [
                'title', 'slug', 'description', 'content', 'url_to_image', 'published_at', 'source', 'category', 'author'
            ]);
        });

        Log::info("Successfully stored articles from all sources.");
    }

    private function fetchArticlesFromSource($source, $url)
    {
        $response = $this->httpClient->get($url);
        $responseData = json_decode($response->getBody()->getContents(), true);

        switch ($source) {
            case 'guardian':
                return $this->processGuardianArticles($responseData);
            case 'newsapi':
                return $this->processNewsApiArticles($responseData);
            case 'nyt':
                return $this->processNYTArticles($responseData);
            default:
                throw new \Exception("Unsupported source: {$source}");
        }
    }

    private function processGuardianArticles($data)
    {
        return collect($data['response']['results'] ?? [])->map(function ($article) {
            return $this->processArticleData(
                'Guardian',
                $article['webTitle'],
                $article['webUrl'],
                $article['webPublicationDate'],
                $article['pillarName'] ?? '',
                $article['author'] ?? null,
                null, // No image provided
                $article['description'] ?? '',
                $article['content'] ?? ''
            );
        })->toArray();
    }

    private function processNewsApiArticles($data)
    {
        return collect($data['articles'] ?? [])->map(function ($article) {
            return $this->processArticleData(
                $article['source']['name'] ?? null,
                $article['title'] ?? '',
                $article['url'] ?? '',
                $article['publishedAt'] ?? now(),
                '',
                $article['author'] ?? null,
                $article['urlToImage'] ?? null,
                $article['description'] ?? '',
                $article['content'] ?? ''
            );
        })->toArray();
    }

    private function processNYTArticles($data)
    {
        return collect($data['results'] ?? [])->map(function ($article) {
            return $this->processArticleData(
                'NYT',
                $article['title'] ?? '',
                $article['url'] ?? '',
                $article['published_date'] ?? now(),
                implode(', ', $article['des_facet'] ?? []),
                $article['byline'] ?? null,
                $article['multimedia'][0]['url'] ?? null,
                $article['abstract'] ?? '',
                ''
            );
        })->toArray();
    }

    private function processArticleData($source, $title, $url, $publishedAt, $category, $author, $image, $description, $content)
    {
        return [
            'source' => $source,
            'author' => $author,
            'title' => $title,
            'slug' => $this->generateSlug($title),
            'description' => $description,
            'content' => $content,
            'url' => $url,
            'url_to_image' => $image,
            'published_at' => Carbon::parse($publishedAt),
            'category' => $category,
        ];
    }

    public function searchArticles(array $filters)
    {
        $query = Article::query();

        if (!empty($filters['keyword'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('title', 'like', '%' . $filters['keyword'] . '%')
                    ->orWhere('description', 'like', '%' . $filters['keyword'] . '%');
            });
        }

        if (!empty($filters['date'])) {
            $query->whereDate('published_at', '=', $filters['date']);
        }

        if (!empty($filters['category'])) {
            $query->where('category', $filters['category']);
        }

        if (!empty($filters['source'])) {
            $query->where('source', $filters['source']);
        }

        return $query->get();
    }
}
