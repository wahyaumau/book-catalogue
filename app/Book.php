<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     title="Book",
 *     description="Book model",
 *     @OA\Xml(
 *         name="Book"
 *     )
 * )
 */
class Book extends Model
{
    /**
     * @OA\Property(
     *     title="id",
     *     description="book id",
     *     format="int64",
     *     example=1
     * )
     *
     * @var integer
     */

     private $id;

     /**
     * @OA\Property(
     *     title="title",
     *     description="book title",
     *     example="Harry Potter"
     * )
     *
     * @var string
     */

    private $title;

    /**
     * @OA\Property(
     *     title="description",
     *     description="book description",     
     *     example="Adventure of Harry Potter"
     * )
     *
     * @var string
     */

    private $description;

    /**
     * @OA\Property(
     *     title="author",
     *     description="book author",     
     *     example="J. K. Rowling"
     * )
     *
     * @var string
     */

    private $author;

    /**
     * @OA\Property(
     *     title="cover_url",
     *     description="book cover url",     
     *     example="https:storage/cover.jpg"
     * )
     *
     * @var string
     */

    private $cover_url;

    /**
     * @OA\Property(
     *     title="release_year",
     *     description="book release year",
     *     format="int64",
     *     example=2004
     * )
     *
     * @var integer
     */

    private $release_year;

    protected $table = 'books';
    protected $fillable = ['title', 'description', 'author', 'cover_url', 'release_year'];
    public $timestamps = false;

    public function reviews() {
        return $this->hasMany('App\Review');
    }
}
