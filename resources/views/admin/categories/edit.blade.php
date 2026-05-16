<x-layouts.admin>

    <div class=" mb-4">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item :href="route('admin.dashboard')">Dashboard</flux:breadcrumbs.item>
            <flux:breadcrumbs.item :href="route('admin.categories.index')">Categorias</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>Editar Categoria</flux:breadcrumbs.item>
        </flux:breadcrumbs>
    </div>

    <div class="w-full md:w-1/2 bg-white dark:bg-gray-800 px-6 py-4 rounded-lg shadow-lg">
        <h2 class="text-xl font-bold mb-4 dark:text-gray-400">Editar Categoria</h2>
        <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <flux:input name="name" label="Nombre" value="{{ old('name', $category->name) }}"
                    class="w-full " />
            </div>

            <div class="w-full flex justify-end">
                <flux:button type="submit" variant="primary" color="sky">
                    Editar
                </flux:button>
            </div>


        </form>
    </div>

</x-layouts.admin>
