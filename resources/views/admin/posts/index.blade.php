<x-layouts.admin>

    <div class="flex justify-between items-center mb-4">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item :href="route('admin.dashboard')">Dashboard</flux:breadcrumbs.item>
            <flux:breadcrumbs.item :href="route('admin.posts.index')">Posts</flux:breadcrumbs.item>
        </flux:breadcrumbs>
    </div>


    {{-- Tabla y botón centrados juntos --}}
    <div class="w-full mx-auto">

        <div class="flex justify-end mb-4">
            <flux:button :href="route('admin.posts.create')" variant="primary" color="blue">
                Crear Post
            </flux:button>
        </div>

        <div class="relative overflow-x-auto shadow-lg sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 mb-4">

                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            ID
                        </th>
                        <th scope="col" class="px-6 py-3">
                            TÍTULO
                        </th>
                        <th scope="col" class="px-6 py-3">
                            EXTRACTO
                        </th>
                        <th scope="col" class="px-6 py-3">
                            CATEGORÍA
                        </th>
                        <th scope="col" class="px-6 py-3">
                            FECHA DE PUBLICACIÓN
                        </th>
                        <th scope="col" class="px-6 py-3">
                            REDACTOR
                        </th>
                        <th scope="col" class="px-6 py-3 text-right">
                            ACCIONES
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($posts as $post)
                        <tr
                            class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700 border-gray-200">
                            <th scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $post->id }}
                            </th>
                            <td class="px-6 py-4">
                                {{ $post->title }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $post->excerpt }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $post->category->name }}
                            </td>
                            <td class="px-6 py-4">
                                @if ($post->published_at)
                                    {{ $post->published_at->format('d/m/Y') }}
                                @else
                                    No publicado
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                {{ $post->user->name }}
                            </td>
                            <td class="px-6 py-4 flex items-center space-x-2 justify-end">
                                <flux:button :href="route('admin.posts.edit', $post)" size="sm"
                                    variant="primary" color="sky">
                                    Editar
                                </flux:button>
                                <form class="delete-form" action="{{ route('admin.posts.destroy', $post) }}"
                                    method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <flux:button type="submit" size="sm" variant="danger" color="red">
                                        Borrar
                                    </flux:button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $posts->links() }}
        </div>

        @push('js')
            <script>
                /*El script se mantiene a la escucha de todos los formularios de eliminación. Los recorre y cada vez
                                                    se envia un formulario , muestra un mensaje de confirmación */
                document.querySelectorAll('.delete-form').forEach(form => {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault(); // Evita el envío del formulario
                        Swal.fire({
                            title: "¿Estás seguro?",
                            text: "No podrás revertir esto!",
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#3085d6",
                            cancelButtonColor: "#d33",
                            confirmButtonText: "Sí, eliminarlo!",
                            cancelButtonText: "Cancelar"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                form.submit();
                            }
                        });
                    });
                });
            </script>
        @endpush

</x-layouts.admin>