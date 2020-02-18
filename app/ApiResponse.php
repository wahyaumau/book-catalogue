<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     title="ApiResponse",
 *     description="Api Response model",
 *     @OA\Xml(
 *         name="ApiResponse"
 *     )
 * )
 */
class ApiResponse
{
    /**
     * @OA\Property(
     *     title="status",
     *     description="request status",
     *     format="int64",
     *     example=200
     * )
     *
     * @var integer
     */

    private $status;

    /**
     * @OA\Property(
     *     title="message",
     *     description="request message",
     *     example="success"
     * )
     *
     * @var string
     */

    private $message;    
}
