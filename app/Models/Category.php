<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //Para poder usar el método factory() en el seeder
    use HasFactory;

   //para que se pueda asignar el nombre de la categoría de forma masiva
   protected $fillable = ['name'];

   //relación de uno a muchos con Post
   public function posts()
   {
       return $this->hasMany(Post::class);
   }

}
