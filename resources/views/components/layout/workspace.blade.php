@props(['breadcrumps' => null, 'title' => null])

<x-layout.core :title="$title">
    <section class="max-w-screen min-h-screen bg-slate-50">
        <x-sidebar :breadcrumps="$breadcrumps"/>
        <main id="main-content" class="md:ps-65 pt-15">
            <div class="p-7">
                {{ $slot }}
            </div>
        </main>
    </section>
</x-layout.core>


