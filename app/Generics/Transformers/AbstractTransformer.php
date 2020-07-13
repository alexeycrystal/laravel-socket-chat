<?php


namespace App\Generics\Transformers;


use App\Modules\Chat\Entities\ChatLinksEntity;

abstract class AbstractTransformer
{
    public static function preparePaginatedMeta(string $apiRouteName,
                                                   int $page,
                                                   int $totalCount,
                                                   int $perPage): ChatLinksEntity
    {
        $routeUrl = route($apiRouteName);

        $firstPage = null;
        $lastPage = null;
        $previousPage = null;
        $currentPage = null;
        $nextPage = null;

        $totalPages = 0;

        if($totalCount && $perPage !== 0) {

            $totalPages = $totalCount > $perPage
                ? ceil($totalCount / $perPage)
                : 1;

            $baseRootAlias = $routeUrl . "?per_page={$perPage}&page=";

            $firstPage = $baseRootAlias . 1;

            $lastPage = $baseRootAlias . $totalPages;

            $previousPage = $totalPages > 1 && $page > 1
                ? $baseRootAlias . ($page - 1)
                : null;

            $currentPage = $baseRootAlias . $page;

            $nextPage = $totalPages > $page
                ? $baseRootAlias . ($page + 1)
                : null;
        }

        return new ChatLinksEntity([
            'first_page' => $firstPage,
            'last_page' => $lastPage,
            'prev_page' => $previousPage,
            'page' => $currentPage,
            'next_page' => $nextPage,
            'per_page' => $perPage,
            'total_pages' => $totalPages,
        ]);
    }
}
