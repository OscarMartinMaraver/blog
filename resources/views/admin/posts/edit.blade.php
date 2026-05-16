<x-layouts.admin>

    @push('css')
        <!-- Incluye el CSS de Quill. Será insertado en el stack de CSS de la plantilla principal -->
        <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />
        {{-- Para lograr que Quill.js (con el tema snow) se integre visualmente con los componentes de Flux y Tailwind CSS, necesitas aplicar algunos estilos CSS personalizados.
        El "problema" con Quill es que genera su propio HTML con clases específicas (.ql-toolbar para la barra de herramientas y .ql-container para el cuadro de texto), 
        las cuales ignoran los estilos globales de tu aplicación. He añadido el código CSS en el archivo estiloQuill.css, y lo importo a app.css --}}

        {{-- Añado el estilo de Select2, para el selector de categorías --}}
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    @endpush

    <div class="mb-4">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item :href="route('admin.dashboard')">Dashboard</flux:breadcrumbs.item>
            <flux:breadcrumbs.item :href="route('admin.posts.index')">Posts</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>Editar Post</flux:breadcrumbs.item>
        </flux:breadcrumbs>
    </div>

    <div class="bg-white px-6 py-4 dark:bg-gray-800 rounded-lg shadow-lg">
        <div class="relative mb-6">
            <img class="w-full md:w-1/2 mx-auto rounded-lg" id="imgPreview"
                src="https://upload.wikimedia.org/wikipedia/commons/d/d1/Image_not_available.png?utm_source=commons.wikimedia.org&utm_campaign=index&utm_content=thumbnail_unscaled&_=20210219185637"
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

                {{-- Div para crear etiquetas con Select2 --}}
                <div class="mb-4 space-x-2 ">
                    <p class="text-sm font-semibold mb-2">Etiquetas</p>
                    <select id="tags" name="tags[]" multiple="multiple" style="width: 100%">
                        @foreach ($tags as $tag)
                        {{-- Uso en value el name, porque he permitido en la función Select2 añadir directamente nuevas etiquetas escribiendolas, por lo que en el array a mandar por POST  se 
                        mezclarían el Id de las opciones predeterminadas con los names de las nuevas etiquetas --}}
                        {{-- La directiva @selected selecciona una opción si es true. Pluck() devuelve una colección basada con los valores de la columna especificada (en este caso id) y toArray() lo convierte en un array.
                        Lo que estoy haciendo es comprobar si el id de la etiqueta está en el array de etiquetas seleccionadas. En caso de error de validación old('tags') devuelve lo que habia almacenado y en caso contrario devuelve 
                        $post->tags->pluck('name')->toArray() --}}
                            <option value="{{ $tag->name }}" @selected(in_array($tag->name, old('tags', $post->tags->pluck('name')->toArray())))>
                                {{ $tag->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- <flux:textarea name="content" label="Contenido" rows=18>{{ old('content', $post->content) }}</flux:textarea> --}}

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
                theme: 'snow',
                placeholder: 'Escribe el contenido de tu post aquí...',
                modules: { //Estos módulos amplian funciones basicas. Las he obtenido de GEMINI
                    // Configuramos la barra de herramientas
                    toolbar: [
                        // Grupo 1: Formato de texto principal (Encabezados y Fuentes)
                        [{'header': [1, 2, 3, false]}],

                        // Grupo 2: Estilos de fuente (Negrita, cursiva, subrayado, tachado)
                        ['bold', 'italic', 'underline', 'strike'],

                        // Grupo 3: Colores de texto y fondo
                        // Al dejar los arrays vacíos, se mostrarán las opciones de color predeterminadas de Quill, que son bastante completas y visualmente agradables.
                        [{'color': []}, {'background': []}],

                        // Grupo 4: Listas y sangrías
                        [{'list': 'ordered'}, {'list': 'bullet'}],[{'indent': '-1'}, {'indent': '+1'}],

                        // Grupo 5: Alineación de texto
                        [{'align': []}],

                        // Grupo 6: Enlaces, imágenes, videos y bloques de código
                        //Está desactivado porque no tengo implementada la subida de imagenes, pero lo dejo por si en un futuro quiero añadir esa función.
                        //['link', 'image', 'video', 'code-block'],

                        // Grupo 7: Limpiar formato (un botón muy útil para borrar estilos copiados)
                        ['clean']
                    ]
                }
            });

            // Tu lógica existente para actualizar el textarea oculto
            quill.on('text-change', function() {
                document.querySelector('#content').value = quill.root.innerHTML;
            });
        </script>

        {{-- Para que funcione Select2 necesito importar jQuery. Elijo el CDN de jQuery Core 4.0.0: minified --}}
        <script src="https://code.jquery.com/jquery-4.0.0.min.js"
            integrity="sha256-OaVG6prZf4v69dPg6PhVattBXkcOWQB62pdZ3ORyrao=" crossorigin="anonymous"></script>

        {{-- Añado Select2 para los campos de selección múltiple en la vista de edición de posts (ctegorias) --}}
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        {{-- Script para inicializar Select2 --}}
        <script>
            $(document).ready(function() {
                $('#tags').select2({
                    tags: true, // Permite crear nuevas opciones
                    tokenSeparators: [','], // Permite separar las etiquetas con comas (cuando el usuario escribe una etiqueta y luego una coma, se crea una nueva etiqueta)
                });
            });
        </script>
    @endpush

</x-layouts.admin>
