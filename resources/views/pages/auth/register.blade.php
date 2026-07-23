<x-layout.core title="Register">
    <section class="bg-slate-50 w-screen h-screen flex  justify-center items-center">
        <x-ui.card class="w-100 m-3">
            <div class="mb-4">
                <h3 class="text-2xl font-semibold">Register</h3>
                <p class="text-sm text-muted">Isi formulir dibawah untuk membuat akun baru!</p>
            </div>
            <form action="{{ route('register.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <x-ui.label for="name" required>Nama</x-ui.label>
                    <x-ui.input type="text" name="name" id="name" placeholder="john" required
                        autocomplete="name" class="w-full" value="{{ old('name') }}"  />
                </div>
                <div class="mb-3">
                    <x-ui.label for="email" required>Email</x-ui.label>
                    <x-ui.input type="email" name="email" id="email" placeholder="john@gmail.com" required
                        autocomplete="email" class="w-full" value="{{ old('email') }}"  />
                </div>
                <div class="mb-3">
                    <x-ui.label for="password" required>Password</x-ui.label>
                    <x-ui.input type="password" name="password" id="password" placeholder="********" required
                        minlength="8" autocomplete="off" class="w-full" />
                </div>
                <div class="mb-3">
                    <x-ui.label for="password_confirmation" required>Password Confirmation</x-ui.label>
                    <x-ui.input type="password" name="password_confirmation" id="password_confirmation"
                        placeholder="********" required minlength="8" autocomplete="off" class="w-full" />
                </div>
                <x-ui.button type="submit" variant="primary" class="w-full mt-4">Register</x-ui.button>
                <p class="my-2 text-sm text-muted text-center">Kembali ke halaman login? <a
                        class="text-blue-500 hover:underline cursor-pointer" href="{{ route('login') }}">Login</a></p>
            </form>
        </x-ui.card>
    </section>
</x-layout.core>
