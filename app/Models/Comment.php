<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['body'];

    /** Relación de muchos a uno con Post
     * Un comentario pertenece a un post, pero un post puede tener muchos comentarios
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }       
}
