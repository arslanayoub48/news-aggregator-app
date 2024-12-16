<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait PaginatorTrait
{
    /**
     * Paginate a query using request parameters.
     *
     * @param Builder $query
     * @param Request $request
     * @param int $defaultPerPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function paginateQuery(Builder $query, Request $request, int $defaultPerPage = 15)
    {
        // Get perPage and page from request, fallback to defaults
        $perPage = (int) $request->input('perPage', $defaultPerPage);
        $page = (int) $request->input('page', 1);

        // Apply pagination
        return $query->paginate($perPage, ['*'], 'page', $page);
    }
}
