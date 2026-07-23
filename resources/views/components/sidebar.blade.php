@props(['breadcrumps' => null])

<aside id="sidebar" class="fixed left-0 bg-white w-65 h-screen border-r-2 border-slate-200 shadow-md z-50 p-5">
    <a href="{{ route('dashboard.index') }}" id="sidebar-title" class="flex items-center gap-1">
        <x-icon.logo/>
        <h3 class="text-2xl text-blue-500 font-semibold">{{ config('app.name') }}</h3>
    </a>

    <div class="mt-7">
        <div class="mb-3">
            <x-sidebar-menu/>
        </div>
    </div>
    <div id="sidebar-overlay" onclick="" class="md:hidden bg-black opacity-10 fixed top-0 left-65 w-full h-screen"></div>
</aside>
<nav id="navbar" class="fixed left-0 top-0 w-full h-15 bg-white shadow-md z-40 md:ps-65 flex items-center justify-between">
    <div class="flex items-center">
        <x-ui.button type="button" variant="ghost" title="Toggle Sidebar" id="toggle-sidebar"><x-icon.sidebar-toggle/></x-ui.button>
        <div class="flex items-center text-sm">
            <a href="#" class="text-muted">Workspace</a>
            @if (isset($breadcrumps))
                @foreach ($breadcrumps as $item)
                    <x-icon.breadcrump-separator/>
                    <a href="{{ $item['url'] }}" class="@if ($item['url'] == '#')
                        text-muted
                    @endif">{{ $item['label'] }}</a>
                @endforeach
            @endif
        </div>
    </div>

    <x-profile/>
</nav>

