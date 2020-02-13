<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table = 'reviews';
    protected $fillable = ['name', 'review', 'rating'];    

    public function reviews() {
        return $this->belongsTo('App\Book');
    }
}
