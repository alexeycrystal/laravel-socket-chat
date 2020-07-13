<?php


namespace App\Generics\Entities;


class LinksGenericEntity extends AbstractEntity
{
    /**
     * @OA\Property(
     *     title="First page url",
     *     description="First page url",
     *     format="string",
     *     nullable=true,
     *     example="http://localhost:8000/api/user/chats?per_page=10&page=1"
     * )
     *
     */
    public $first_page;

    /**
     * @OA\Property(
     *     title="Previous page url",
     *     description="Previous page url",
     *     format="string",
     *     nullable=true,
     *     example="http://localhost:8000/api/user/chats?per_page=10&page=2"
     * )
     **/
    public $prev_page;

    /**
     * @OA\Property(
     *     title="Current page url",
     *     description="Current page url",
     *     format="string",
     *     nullable=true,
     *     example="http://localhost:8000/api/user/chats?per_page=10&page=3"
     * )
     *
     */
    public $page;

    /**
     * @OA\Property(
     *     title="Next page url",
     *     description="Next page url",
     *     format="string",
     *     nullable=true,
     *     example="http://localhost:8000/api/user/chats?per_page=10&page=4"
     * )
     *
     */
    public $next_page;

    /**
     * @OA\Property(
     *     title="Last page url",
     *     description="Last page url",
     *     format="string",
     *     example="http://localhost:8000/api/user/chats?per_page=10&page=20"
     * )
     *
     */
    public $last_page;

    /**
     * @OA\Property(
     *     title="Elements per page",
     *     description="Elements per page",
     *     format="int32",
     *     example="10"
     * )
     *
     * @var int
     */
    public int $per_page;

    /**
     * @OA\Property(
     *     title="Total pages",
     *     description="Total pages",
     *     format="int32",
     *     example="30"
     * )
     *
     * @var int
     */
    public int $total_pages;
}
