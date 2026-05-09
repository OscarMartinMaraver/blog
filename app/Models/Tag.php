<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['name'];

    /** Relación de muchos a muchos con Post
     * Un post puede tener muchos tags, y un tag puede pertenecer a muchos posts
     */
    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }
}
