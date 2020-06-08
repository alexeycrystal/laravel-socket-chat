<?php


namespace App\Generics\Transformers;


abstract class AbstractTransformer
{
    public static function preparePaginatedMeta(string $apiRouteName,
                                                   int $page,
                                                   int $totalCount,
                                                   int $perPage): array
    {
        $routeUrl = route($apiRouteName);

        $firstPage = null;
        $lastPage = null;
        $previousPage = null;
        $nextPage = null;

        $totalPages = 0;

        if($totalCount && $perPage !== 0) {

            $totalPages = $totalCount > $perPage
                ? ceil($totalCount / $perPage)
                : 1;

            $baseRootAlias = config('app.url') . $routeUrl . "?per_page={$perPage}&page=";

            $firstPage = $baseRootAlias . 1;

            $lastPage = $baseRootAlias . $totalPages;

            $previousPage = $totalPages > 1 && $page > 1
                ? $baseRootAlias . ($page - 1)
                : null;

            $nextPage = $totalPages > $page
                ? $baseRootAlias . ($page + 1)
                : null;
        }

        return [
            'first_page' => $firstPage,
            'last_page' => $lastPage,
            'prev_page' => $previousPage,
            'next_page' => $nextPage,
            'per_page' => $perPage,
            'total_pages' => $totalPages,
        ];
    }
}
