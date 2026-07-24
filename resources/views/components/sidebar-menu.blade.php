<p class="text-sm text-muted uppercase mb-2" >Utama</p>
<ul class="ms-2 flex flex-col gap-1">
    <li>
        <a href="{{ route('dashboard.index') }}" class="flex gap-2 items-center w-full p-3 hover:bg-slate-200 rounded-md cursor-pointer
        @if (request()->routeIs('dashboard.index'))
            sidebar-menu-active
        @endif"><x-icon.dashboard fill="{{ request()->routeIs('dashboard.index') ? '#ffff' : '#8E98A6' }}" />Dashboard</a>
    </li>
    <li>
        <a href="{{ route('transactions.index') }}" class="flex gap-2 items-center w-full p-3 hover:bg-slate-200 rounded-md cursor-pointer
        @if (request()->routeIs('transactions.index'))
            sidebar-menu-active
        @endif"><x-icon.transaction fill="{{ request()->routeIs('transactions.index') ? '#ffff' : '#8E98A6' }}" />Transaksi</a>
    </li>
    <li>
        <a href="{{ route('pockets.index') }}" class="flex gap-2 items-center w-full p-3 hover:bg-slate-200 rounded-md cursor-pointer
        @if (request()->routeIs('pockets.index'))
            sidebar-menu-active
        @endif"><x-icon.kantong fill="{{ request()->routeIs('pockets.index') ? '#ffff' : '#8E98A6' }}" />Kantong</a>
    </li>
</ul>

<style>
    .sidebar-menu-active{
        background-color:#2B7FFF;
        color: white;
    }
    .sidebar-menu-active:hover{
        background-color:#155DFC;
    }
</style>
