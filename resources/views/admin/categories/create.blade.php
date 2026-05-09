<x-layouts.admin>

    <div class=" mb-4">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item :href="route('admin.dashboard')">Dashboard</flux:breadcrumbs.item>
            <flux:breadcrumbs.item :href="route('admin.categories.index')">Categorias</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>Crear Categoria</flux:breadcrumbs.item>
        </flux:breadcrumbs>
    </div>

    <div class="bg-white px-6 py-4 rounded-lg shadow-lg">
        <h2 class="text-xl font-bold mb-4">Crear Categoria</h2>
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <flux:input name="name" label="Nombre" value="{{ old('name') }}"
                    class="w-full md:w-1/2" />
            </div>

            <div class="w-full md:w-1/2 flex justify-end">
                <flux:button type="submit" variant="primary" color="blue">
                    Crear
                </flux:button>
            </div>


        </form>
    </div>

</x-layouts.admin>
