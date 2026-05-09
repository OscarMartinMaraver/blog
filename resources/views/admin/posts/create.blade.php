<x-layouts.admin>

    <div class=" mb-4">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item :href="route('admin.dashboard')">Dashboard</flux:breadcrumbs.item>
            <flux:breadcrumbs.item :href="route('admin.posts.index')">Posts</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>Crear Post</flux:breadcrumbs.item>
        </flux:breadcrumbs>
    </div>

    <div class="bg-white px-6 py-4 rounded-lg shadow-lg">
        <h2 class="text-xl font-bold mb-4">Crear Post</h2>
        <form action="{{ route('admin.posts.store') }}" method="POST">
            @csrf
            <div class="mb-4 w-full md:w-1/2 space-y-4">
                <flux:input name="title" label="Título" oninput="string_to_slug(this.value, '#slug')" value="{{ old('title') }}" />
                <flux:input name="slug" id="slug" label="Slug" value="{{ old('slug') }}" />
                <flux:select name="category_id" label="Categoría" placeholder="Elija una categoría...">
                    @foreach ($categories as $category)
                        <flux:select.option value="{{ $category->id }}" :selected="$category->id == old('category_id')">
                            {{ $category->name }}
                        </flux:select.option>
                    @endforeach
                </flux:select>
            </div>

            <div class="w-full md:w-1/2 flex justify-end">
                <flux:button type="submit" variant="primary" color="blue">
                    Crear
                </flux:button>
            </div>
        </form>
    </div>

    {{-- 
    Script para convertir el título en slug. Se puede colocar directamente aquí o en un archivo js aparte que se importe en app.js. 
    En este caso lo he colocado aquí para mostrar cómo usar @push('js') y @stack('js') para renderizar scripts adicionales en la vista hija. 
     
    @push('js')
        <script>
            function string_to_slug(str, querySelector) {
                // Eliminar espacios al inicio y final
                str = str.replace(/^\s+|\s+$/g, '');

                // Convertir todo a minúsculas
                str = str.toLowerCase();

                // Definir caracteres especiales y sus reemplazos
                var from = "àáäâèéëêìíïîòóöôùúüûñç·/_,:;";
                var to = "aaaaeeeeiiiioooouuuunc------";

                // Reemplazar caracteres especiales por los correspondientes en 'to'
                for (var i = 0, l = from.length; i < l; i++) {
                    str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
                }

                // Eliminar caracteres no alfanuméricos y reemplazar espacios por guiones
                str = str.replace(/[^a-z0-9 -]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-');

                // Asignar el slug generado al campo de entrada correspondiente
                document.querySelector(querySelector).value = str;
            }
        </script>
    @endpush 
    
    --}}
</x-layouts.admin>
