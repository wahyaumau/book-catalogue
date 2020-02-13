<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $table = 'books';
    protected $fillable = ['title', 'description', 'author', 'cover_url', 'release_year'];
    public $timestamps = false;

    public function reviews() {
        return $this->hasMany('App\Review');
    }
}
