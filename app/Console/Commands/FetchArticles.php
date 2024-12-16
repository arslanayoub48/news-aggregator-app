<?php

namespace App\Console\Commands;

use App\Services\ArticleService;
use Illuminate\Console\Command;

class FetchArticles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-articles';
    protected $articleService;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command fetches articles';

    /**
     * Execute the console command.
     */
    public function __construct(ArticleService $articleService)
    {
        parent::__construct();
        $this->articleService = $articleService;
    }
    public function handle()
    {
        $this->articleService->fetchAndStoreArticles();
    }
}
