<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Posts extends Model
{
    use HasFactory;
    protected $fillable = [
        'description',
        'images',
        'likes',
        'comments',
        'user_id'
    ];


    public function likes()
    {
        return $this->hasMany(likes::class, 'post_id');
    }

    public function comments()
    {
        return $this->hasMany(comment::class, 'post_id');
    }


}
