@props(['breadcrumps' => null])

<x-layout.core title="Dashboard">
    <section class="max-w-screen min-h-screen bg-slate-50">
        <x-sidebar :breadcrumps="$breadcrumps"/>
        <main id="main-content" class="md:ps-65 pt-15">
            <div class="p-7">
                <x-ui.card class="w-full h-fit">
                    {{ $slot }}
                </x-ui.card>
            </div>
        </main>
    </section>
</x-layout.core>
