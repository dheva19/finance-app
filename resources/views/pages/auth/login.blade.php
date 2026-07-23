<x-layout.core title="Login">
    <section class="bg-slate-50 w-screen h-screen flex justify-center items-center">
        <x-ui.card class="w-100 m-3">
            <div class="mb-4">
                <h3 class="text-2xl font-semibold">Login</h3>
                <p class="text-sm text-muted">Gunakan email dan password untuk masuk!</p>
            </div>
            <form action="{{ route('login.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <x-ui.label for="email" required>Email</x-ui.label>
                    <x-ui.input type="email" name="email" id="email" placeholder="john@gmail.com" required
                        autocomplete="email" class="w-full" value="{{ old('email') }}" />
                </div>
                <div class="mb-3">
                    <x-ui.label for="password" required>Password</x-ui.label>
                    <x-ui.input type="password" name="password" id="password" placeholder="********" required
                    minlength="8" autocomplete="off" class="w-full" />
                </div>
                <div class="flex gap-1 items-center">
                    <input type="checkbox" name="remember" id="remember" class="cursor-pointer">
                    <label for="remember" class="text-sm text-muted cursor-pointer">Remember Me</label>
                </div>
                <x-ui.button type="submit" variant="primary" class="w-full mt-4">Login</x-ui.button>
                <p class="my-2 text-sm text-muted text-center">Belum punya akun? <a
                        class="text-blue-500 hover:underline cursor-pointer" href="{{ route('register.index') }}">Buat
                        Akun</a></p>
            </form>
        </x-ui.card>
    </section>
</x-layout.core>
