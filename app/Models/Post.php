<?php

namespace App\Models;

use App\Observers\PostObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//Registro del observer para el modelo Post
#[ObservedBy(PostObserver::class)]

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'image_path',
        'excerpt',
        'content',
        'is_published',
        'published_at',
        'user_id',
        'category_id',
    ];

    //para que laravel sepa que is_published es un booleano y published_at es una fecha
    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    //RELACIONES UNA A MUCHOS INVERSA CON USER Y CATEGORY

    /**relación con la categoría */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**relación con el usuario */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //RELACION UNO A MUCHOS CON COMMENT

    /** Un post puede tener muchos comentarios, pero un comentario solo pertenece a un post */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    //RELACION MUCHOS A MUCHOS CON TAG
    /** Un post puede tener muchos tags, y un tag puede pertenecer a muchos posts */
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}
