{{-- Las plantillas por defecto llaman a componentes unos dentros de otros.
En este caso, para administrar el área de administración se ha construido una plantilla específica que contiene los elementos necesarios todos dentro
de la propia plantilla. Se ha usado como base layouts.app.sidebar.blade.php (posteriormente se ha eliminado la carpeta app) el contenido de la cabecera que está en partials.head --}}

@props(['title' => 'Proyecto blog']) {{-- Si no existe title, se usa el valor por defecto --}}
{{-- @props es una directiva usada dentro de componentes de Blade para declarar las propiedades que ese componente espera recibir desde quien lo invoca.
En este caso, se declara una propiedad llamada title con un valor por defecto de 'Proyecto blog'. Esto significa que si el componente se utiliza
sin proporcionar un valor para title, automáticamente se asignará 'Proyecto blog' a esa propiedad. --}}

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    {{-- @include('partials.head') --}}
    {{-- En lugar de usar el enlace para la cabecera, se incluye directamente --}}
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    {{-- <title>{{ $title ?? 'Laravel' }}</title> --}}
    {{-- Ahora se usa el título proporcionado o el por defecto con @props --}}
    <title>{{ $title }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    {{-- Script para cargar los estilos de SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @stack ('css') {{-- Para cargar estilos adicionales que se hayan definido en otras vistas. En la vista hija se usa @push('css') --}}

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fluxAppearance
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800">
    <flux:sidebar sticky stashable class="border-r border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

        <a href="{{ route('admin.dashboard') }}" class="mr-5 flex items-center space-x-2" wire:navigate>
            <x-app-logo class="size-8" href="#"></x-app-logo>
        </a>

        <flux:navlist variant="outline">
            <flux:navlist.group heading="Platform" class="grid">
                <flux:navlist.item icon="home" :href="route('admin.dashboard')"
                    :current="request()->routeIs('admin.dashboard')" wire:navigate>Dashboard
                </flux:navlist.item>
                <flux:navlist.item icon="tag" :href="route('admin.categories.index')"
                    :current="request()->routeIs('admin.categories.*')" wire:navigate>Categorias
                </flux:navlist.item>
                <flux:navlist.item icon="book-open" :href="route('admin.posts.index')"
                    :current="request()->routeIs('admin.posts.*')" wire:navigate>Posts
                </flux:navlist.item>
            </flux:navlist.group>
        </flux:navlist>

        <flux:spacer />

        <!-- Desktop User Menu -->
        <flux:dropdown position="bottom" align="start">
            <flux:profile :name="auth()->user()->name" :initials="auth()->user()->initials()"
                icon-trailing="chevrons-up-down" />

            <flux:menu class="w-[220px]">
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                            <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                <span
                                    class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                    {{ auth()->user()->initials() }}
                                </span>
                            </span>

                            <div class="grid flex-1 text-left text-sm leading-tight">
                                <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.radio.group>
                    <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>Ajustes
                    </flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                        {{ __('Log Out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:sidebar>

    <!-- Mobile User Menu -->
    <flux:header class="lg:hidden">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

        <flux:spacer />

        <flux:dropdown position="top" align="end">
            <flux:profile :initials="auth()->user()->initials()" icon-trailing="chevron-down" />

            <flux:menu>
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                            <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                <span
                                    class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                    {{ auth()->user()->initials() }}
                                </span>
                            </span>

                            <div class="grid flex-1 text-left text-sm leading-tight">
                                <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.radio.group>
                    <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>Ajustes
                    </flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                        {{ __('Log Out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:header>

    {{-- {{ $slot }} --}}
    {{-- En lugar de usar el slot, se incluye directamente --}}
    <flux:main>
        {{ $slot }}
    </flux:main>

    @fluxScripts

    {{-- El contenido de session('swal') hay que pasárselo al método Swal.fire, pero lo que le paso es una array php
    y lo que espera swal.fire es un objeto JavaScript, por lo que lo convertimos a JSON --}}

    @if (session('swal'))
        <script>
            Swal.fire(@json(session('swal')))
        </script>
    @endif

    {{-- Otra forma seria:
    
    @if (session('swal'))
        <script>
            Swal.fire({
                title: '{{ session('swal')['title'] }}',
                text: '{{ session('swal')['text'] }}',
                icon: '{{ session('swal')['icon'] }}',
            });
        </script>
    @endif
     --}}

     {{-- Renderizar scripts adicionales que se hayan definido en otras vistas
     En la vista hija usa @push('js'). Aquí se renderizan  por ejemplo el script para convertir el título en slug, o
     el script para confirmar la eliminación de una categoria o post--}}
     @stack('js')

</body>

</html>
