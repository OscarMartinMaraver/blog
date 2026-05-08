{{-- En lugar de usar elmentos que llamen unos a otros, igual que se ha hecho con la creacion de layouts.admin (usa como base layouts.app.sidebar.blade.php), se prepara una plantilla
para el usuario normal añadiendo los elementos necesarios (se usa como base layouts.header.blade.php), pero esta vez sin crear una plantilla nueva,
sino que se sustituye el contenido qu traia por defecto layouts.app.blade.php --}}

{{-- <x-layouts.app.header :title="$title ?? null">
    <flux:main>
        {{ $slot }}
    </flux:main>
</x-layouts.app.header> --}}


@props(['title' => 'Proyecto blog']) {{-- Si no existe title, se usa el valor por defecto --}}
{{-- @props es una directiva usada dentro de componentes de Blade para declarar las propiedades que ese componente espera recibir desde quien lo invoca.
En este caso, se declara una propiedad llamada title con un valor por defecto de 'Proyecto blog'. Esto significa que si el componente se utiliza
sin proporcionar un valor para title, automáticamente se asignará 'Proyecto blog' a esa propiedad. --}}

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    {{-- @include('partials.head') --}}
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    {{-- <title>{{ $title ?? 'Laravel' }}</title> --}}
    <title>{{ $title }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fluxAppearance
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800">
    <flux:header container class="border-b border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

        <a href="{{ route('home') }}" class="ml-2 mr-5 flex items-center space-x-2 lg:ml-0" wire:navigate>
            <x-app-logo class="size-8" href="#"></x-app-logo>
        </a>

        <flux:navbar class="-mb-px max-lg:hidden">
            <flux:navbar.item icon="layout-grid" href="{{ route('home') }}"
                :current="request()->routeIs('home')" wire:navigate>
                Home
            </flux:navbar.item>
        </flux:navbar>

        <flux:spacer />

        <flux:navbar class="mr-1.5 space-x-0.5 py-0!">
            <flux:tooltip content="Search" position="bottom">
                <flux:navbar.item class="!h-10 [&>div>svg]:size-5" icon="magnifying-glass" href="#"
                    label="Search" />
            </flux:tooltip>
        </flux:navbar>

        <!-- Desktop User Menu -->
        @auth
        {{-- Si no encierro este contenido en la directiva @auth, en caso de no estar autenticado, se mostraría un error debido a que se intenta acceder a la información del usuario --}}
            <flux:dropdown position="top" align="end">
                <flux:profile class="cursor-pointer" :initials="auth()->user()->initials()" />

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
                        <flux:menu.item :href="route('admin.dashboard')" icon="key" wire:navigate>Admin</flux:menu.item>
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
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
        @else
            <flux:dropdown position="top" align="end">
                <flux:button icon:trailing="user" class="cursor-pointer"/>

                <flux:menu>
                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('login')" wire:navigate>
                            {{ __('Login') }}
                        </flux:menu.item>
                        <flux:menu.item :href="route('register')" wire:navigate>
                            {{ __('Register') }}
                        </flux:menu.item>
                    </flux:menu.radio.group>
                </flux:menu>
            </flux:dropdown>

        @endauth
    </flux:header>

    <!-- Mobile Menu -->
    <flux:sidebar stashable sticky
        class="lg:hidden border-r border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

        <a href="{{ route('home') }}" class="ml-1 flex items-center space-x-2" wire:navigate>
            <x-app-logo class="size-8" href="#"></x-app-logo>
        </a>

        <flux:navlist variant="outline">
            <flux:navlist.group heading="Platform">
                <flux:navlist.item icon="layout-grid" href="{{ route('home') }}"
                    :current="request()->routeIs('home')" wire:navigate>
                    Home
                </flux:navlist.item>
            </flux:navlist.group>
        </flux:navlist>

    </flux:sidebar>

    {{-- {{ $slot }} --}}
    {{-- En lugar de usar el slot, se incluye directamente --}}
    <flux:main>
        {{ $slot }}
    </flux:main>

    @fluxScripts
</body>

</html>
