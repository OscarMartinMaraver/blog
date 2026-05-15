<x-layouts.admin>

    @push('css')
        <!-- Incluye el CSS de Quill. Será insertado en el stack de CSS de la plantilla principal -->
        <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />
    @endpush

    <div class="mb-4">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item :href="route('admin.dashboard')">Dashboard</flux:breadcrumbs.item>
            <flux:breadcrumbs.item :href="route('admin.posts.index')">Posts</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>Editar Post</flux:breadcrumbs.item>
        </flux:breadcrumbs>
    </div>

    <div class="bg-white px-6 py-4 dark:bg-gray-800 rounded-lg shadow-lg">
        <div class="relative mb-2">
            <img class="w-full mx-auto rounded-lg" id="imgPreview"
                src="https://www.shutterstock.com/image-vector/no-image-photography-vector-icon-260nw-1736999849.jpg"
                alt="Imagen del post">
            <div class="absolute top-6 right-6">
                {{-- <flux:button variant="primary" color="blue" size="sm">
                    Cambiar Imagen
                </flux:button> --}}
                <label
                    class="bg-blue-500 border border-gray-300 rounded-lg py-2 px-4 cursor-pointer text-sm font-bold text-white hover:bg-blue-600">
                    {{-- coloco un label en lugar de button porque me permite añadir input type="file" --}}
                    <input type="file" name="image" class="hidden" accept="image/*"
                        onchange="preview_image(event, '#imgPreview')">
                    Seleccionar Imagen
                </label>
            </div>
        </div>
        <h2 class="text-xl font-bold mb-4 dark:text-gray-400">Editar Post</h2>
        <form action="{{ route('admin.posts.update', $post->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4 w-full md:w-1/2 space-y-4">
                <flux:input name="title" label="Título" oninput="string_to_slug(this.value, '#slug')"
                    value="{{ old('title', $post->title) }}" />
                <flux:input name="slug" id="slug" label="Slug" value="{{ old('slug', $post->slug) }}" />
                <flux:select name="category_id" label="Categoría">
                    @foreach ($categories as $category)
                        <flux:select.option value="{{ $category->id }}"
                            :selected="$category->id == old('category_id', $post->category_id)">{{-- Selected muestra un contido si se evalua a true --}}
                            {{ $category->name }}
                        </flux:select.option>
                    @endforeach
                </flux:select>
            </div>
            <div class="mb-4 w-full space-y-4">
                <flux:textarea name="excerpt" label="Extracto">{{ old('excerpt', $post->excerpt) }}</flux:textarea>
                {{-- <flux:textarea name="content" label="Contenido" rows=18>{{ old('content', $post->content) }}
            </flux:textarea> --}}
                {{-- La estructura de Quill.js (ver manual en su pagina) contempla unas etiquedas div con un identificador que será usado por js para inicializar el editor,
            pero el problema es que una etiqueta div no puede ser utilizado en un formulario para enviar información, por lo que no puedo actualizar el contenido del post 
            Para solucionarlo creamos dentro del div un textarea y lo ocultamos. En el script añadimos una función que vaya capturando el contenido del div y 
            lo vaya actualizando en el textarea a traves del id. Así el contenido del textarea se enviará al servidor y se actualizará el post. 

            qill.on('text-change', function() {
            document.querySelector('#content').value = quill.root.innerHTML;});

            Para que en div no se muestren las etiquetas html del contenido del post, se usa {!! !!} en lugar de {{ }} para mostrar el contenido sin escapar las etiquetas html.
            
            --}}
                <div>
                    <p class="font-medium text-sm mb-2">Contenido</p>
                    <div id="editor">{!! old('content', $post->content) !!}</div>
                    <textarea name="content" id="content" class='hidden'>{{ old('content', $post->content) }}</textarea>
                </div>
            </div>

            <div class="mb-4 space-x-2">
                <p class="text-sm font-semibold">Estado</p>
                <label>
                    <input type="radio" name="is_published" value="0" @checked(old('is_published', $post->is_published) == 0)>
                    No Publicado
                </label>
                <label>
                    <input type="radio" name="is_published" value="1" @checked(old('is_published', $post->is_published) == 1)>
                    Publicado
                </label>
            </div>

            <div class="w-full flex justify-end">
                <flux:button type="submit" variant="primary" color="blue">
                    Guardar Cambios
                </flux:button>
            </div>
        </form>
    </div>

    @push('js')
        <!-- Include the Quill library -->
        <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
        <script>
            const quill = new Quill('#editor', {
                theme: 'snow'
            });

            quill.on('text-change', function() {
                document.querySelector('#content').value = quill.root.innerHTML;
            });
        </script>
    @endpush

</x-layouts.admin>
