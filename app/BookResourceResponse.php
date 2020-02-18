<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     title="Book Resource Response",
 *     description="Book Resource Response model",
 *     @OA\Xml(
 *         name="BookResourceResponse"
 *     )
 * )
 */
class BookResourceResponse extends ApiResponse
{
    /**
     * @OA\Property(
     *     title="result",
     *     description="Book resource result"
     * )
     *
     * @var \App\Book[]
     */
    private $result;
}
