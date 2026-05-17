<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

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
            //'slug' => 'required|string|max:255|unique:posts,slug,' . $post->id,
            /*Quiero que slug no se muestre una vez sea publicado el post, pero al actualizar de nuevo el post, da error.
                Se puede usar Rule, cuya ventaja es que permite usar condiciones para validar el campo slug, en este caso, el campo slug es requerido si el post no tiene fecha de publicación, 
                es decir, si no está publicado, y además debe ser único en la tabla posts, pero ignorando el id del post que se está actualizando para evitar el error de clave única al actualizar un post sin cambiar el slug.*/
            'slug' =>[
                Rule::requiredIf(function () use($post){
                    return !$post->published_at; //Si el post no tiene fecha de publicación (entonces returntrue), el campo slug es requerido, de lo contrario no es requerido
                }),
                'string',
                'max:255',
                Rule::unique('posts', 'slug')
                ->ignore($post->id), //El campo slug debe ser único en la tabla posts, pero ignorando el id del post que se está actualizando para evitar el error de clave única al actualizar un post sin cambiar el slug.
            ],
            'image' => 'nullable|image|max:2048',
            'category_id' => 'required|exists:categories,id',
            'excerpt' => 'required_if:is_published,1|string',
            'content' => 'required_if:is_published,1|string',
            'tags' => 'array',
            'is_published' => 'boolean',

        ]);

        if($request->hasFile('image')){
              /* Storage::disk('local')->put('post', $request->image);Almacena la imagen en el disco local, en la carpeta post, con un nombre generado automáticamente por Laravel para evitar colisiones de nombres.
              Si no se indica disk, se almacena en el disco por defecto definido en el archivo .env, FILESYSTEM_DISK */
              if($post->image_path){
                Storage::delete($post->image_path); //Elimina la imagen anterior del almacenamiento público
              }
              $data['image_path'] = $request->image->store('post', 'public');

        }

        //Mediante un observer, al actualizar un post, si el checkbox de publicar esta marcado y el post no tiene fecha de publicación, 
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
