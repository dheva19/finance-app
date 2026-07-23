
@if (isset(Auth::user()->name))
    <span onclick="document.querySelector('#profile-dropdown').classList.toggle('hidden');" class="flex items-center gap-2 me-3 cursor-pointer hover:bg-slate-50 p-1 rounded-md select-none">
        <div class="bg-slate-100 flex justify-center items-center rounded-full w-10 h-10">
            <p class="font-semibold text-md text-blue-500">
                {{ Auth::user()->getInitial() }}
            </p>
        </div>
        <div class="hidden md:block">
            <p class="text-sm font-semibold">{{ substr(Auth::user()->name, 0, 15) }}@if(Str::length(Auth::user()->name) > 15)
            ...
            @endif</p>
            <p class="text-xs text-muted">{{ substr(Auth::user()->email, 0, 15) }}@if(Str::length(Auth::user()->email) > 15)
            ...
            @endif</p>
        </div>
    </span>

    <form id="logout-form" action="{{ route('logout.store') }}" method="POST" class="hidden">
        @csrf
    </form>

    <script>
        function handleLogout(){
            Swal.fire({
                title: "Konfirmasi Aksi!",
                text: "Apakah anda yakin ingin keluar?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Keluar",
                cancelButtonText: "Batal"
                }).then((result) => {
                if (result.isConfirmed) document.querySelector('#logout-form').submit();
            });
        }
    </script>

    <div id="profile-dropdown" class="hidden fixed w-40 right-2 top-16 bg-white p-2 rounded-md shadow-md border border-slate-200">
        <ul>
            <li class="flex items-center gap-1 text-sm cursor-pointer hover:bg-slate-100 p-2 rounded-md" onclick="handleLogout()"><x-icon.logout/>Logout</li>
        </ul>
    </div>
@endif
