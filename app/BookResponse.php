<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


/**
 * @OA\Schema(
 *     title="Book Response",
 *     description="Book Response model",
 *     @OA\Xml(
 *         name="BookResponse"
 *     )
 * )
 */
class BookResponse extends ApiResponse
{
    /**
     * @OA\Property(
     *     title="result",
     *     description="Book result"
     * )
     *
     * @var \App\Book
     */
    private $result;
}
