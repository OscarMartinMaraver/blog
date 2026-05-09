<x-layouts.admin>

    <div class=" mb-4">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item :href="route('admin.dashboard')">Dashboard</flux:breadcrumbs.item>
            <flux:breadcrumbs.item :href="route('admin.posts.index')">Posts</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>Editar Post</flux:breadcrumbs.item>
        </flux:breadcrumbs>
    </div>

    <div class="bg-white px-6 py-4 rounded-lg shadow-lg">
        <h2 class="text-xl font-bold mb-4">Editar Post</h2>
        <form action="{{ route('admin.posts.update', $post->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4 w-full md:w-1/2 space-y-4">
                <flux:input name="title" label="Título" oninput="string_to_slug(this.value, '#slug')" value="{{ old('title', $post->title) }}" />
                <flux:input name="slug" id="slug" label="Slug" value="{{ old('slug', $post->slug) }}" />
                <flux:select name="category_id" label="Categoría">
                    @foreach ($categories as $category)
                        <flux:select.option value="{{ $category->id }}"
                            :selected="$category->id == old('category_id', $post->category_id)">{{--Selected muestra un contido si se evalua a true--}}
                            {{ $category->name }}
                        </flux:select.option>
                    @endforeach
                </flux:select>
            </div>
            <div class="mb-4 w-full space-y-4">
                <flux:textarea name="excerpt" label="Extracto">{{ old('excerpt', $post->excerpt) }}</flux:textarea>
                <flux:textarea name="content" label="Contenido" rows=18>{{ old('content', $post->content) }}</flux:textarea>
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
                    Editar
                </flux:button>
            </div>
        </form>
    </div>

</x-layouts.admin>
