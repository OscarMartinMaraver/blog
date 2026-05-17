<?php

namespace App\Observers;

use App\Models\Post;

class PostObserver
{
    //Un observer es una clase que se encarga de escuchar los eventos que ocurren en un modelo, como la creación, actualización o eliminación de un registro. 
    //En este caso, el PostObserver se encargará de escuchar el evento de actualización de un post y asignar la fecha de publicación si el checkbox de publicar 
    //está marcado y el post no tiene fecha de publicación.

    //Para que funcione hay que registrar el observer en el modelo Post, en el método boot del AppServiceProvider o en un service provider específico para observers.

    //Otros ejemplos son updated, created, creating, deleted, deleting, restored, restoring, forceDeleted, forceDeleting, retrieved, saving, saved, updating, updated.   
    
    public function updating(Post $post){

    if ($post->is_published==1 && !$post->published_at) {
            $post->published_at = now();
        }
        elseif ($post->is_published==0) {
            $post->published_at = null;
        }

        // Si el código estuviera directamente en el controlador, método update
        // if ($request->is_published==1 && !$post->published_at) {
        //     $data['published_at'] = now();}
    }
        
}
