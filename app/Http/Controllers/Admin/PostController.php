<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::orderBy('id', 'desc')->paginate(10);

        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();

        return view('admin.posts.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //Validacion de datos añadidos por el usuario
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:posts,slug',
            'category_id' => 'required|exists:categories,id',
        ]);

        //Agregamos el id del usuario autenticado al array de datos
        $data['user_id'] = auth('web')->id();

        $post = Post::create($data);

        session()->flash('swal', [
            'title' => 'Post Creado',
            'text' => 'El post ha sido creado exitosamente.',
            'icon' => 'success',
        ]);
        return redirect()->route('admin.posts.edit', $post);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return view('admin.posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        $categories = Category::all();
        $tags= Tag::all();

        return view('admin.posts.edit', compact('post', 'categories', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:posts,slug,' . $post->id,
            'category_id' => 'required|exists:categories,id',
            'excerpt' => 'required_if:is_published,1|string',
            'content' => 'required_if:is_published,1|string',
            'tags' => 'array',
            'is_published' => 'boolean',

        ]);

        //MEdiante un observer, al actualizar un post, si el checkbox de publicar esta marcado y el post no tiene fecha de publicación, 
        // se asigna la fecha actual a published_at, de lo contrario se asigna null. Esto se hace con un método updating en el app/observers
        $post->update($data);

        $tags = [];

        foreach ($request->tags ?? [] as $tag){ //Si tags no existe(no añado inguna etiqeuta en plantilla edit), se asigna un array vacío para evitar errores
            $tags[] = Tag::firstOrCreate(['name' => $tag]);//Busca la etiqueta por su nombre, si no existe la crea y devuelve su id, si existe devuelve su id
        }

        //Accedemos a la relación de etiquetas del post y sincronizamos con el array de ids de etiquetas, eliminando las que no estén en el array y añadiendo las nuevas
        $post->tags()->sync($tags);
        

        session()->flash('swal', [
            'title' => 'Post Actualizado',
            'text' => 'El post ha sido actualizado exitosamente.',
            'icon' => 'success',
        ]);
       
        return redirect()->route('admin.posts.edit', $post);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();

        session()->flash('swal', [
            'title' => 'Post Eliminado',
            'text' => 'El post ha sido eliminado exitosamente.',
            'icon' => 'success',
        ]);

        return redirect()->route('admin.posts.index');
    }
}
