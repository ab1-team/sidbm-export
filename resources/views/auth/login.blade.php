<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- Logo & Header -->
    <div class="text-center mb-8">
        <!-- Logo -->
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-primary to-primary-600 shadow-lg mb-6">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
        </div>

        <h1 class="text-2xl font-semibold text-text mb-1">Selamat Datang</h1>
        <p class="text-secondary text-sm">Masuk untuk melanjutkan ke sistem</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-text mb-2">Email</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-secondary">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                    </svg>
                </span>
                <input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username"
                    class="w-full pl-12 pr-4 py-3 rounded-xl border border-border bg-white text-text placeholder-secondary/60 shadow-input focus:shadow-none focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200"
                    placeholder="nama@email.com" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 text-sm" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-text mb-2">Password</label>
            <div class="relative" x-data="{ show: false }">
                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-secondary">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </span>
                <input id="password" :type="show ? 'text' : 'password'" name="password" required autocomplete="current-password"
                    class="w-full pl-12 pr-12 py-3 rounded-xl border border-border bg-white text-text placeholder-secondary/60 shadow-input focus:shadow-none focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200"
                    placeholder="Masukkan password" />
                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-4 flex items-center text-secondary hover:text-primary transition-colors">
                    <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <svg x-show="show" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500 text-sm" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="w-4 h-4 rounded border-border text-primary focus:ring-primary/20 cursor-pointer" name="remember">
                <span class="ml-2 text-sm text-secondary">Ingat saya</span>
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm text-primary hover:text-primary-600 transition-colors">
                    Lupa password?
                </a>
            @endif
        </div>

        <!-- Submit Button -->
        <button type="submit" class="w-full py-3 px-4 bg-primary text-white font-medium rounded-xl shadow-lg hover:shadow-xl hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:ring-offset-2 transition-all duration-200 transform hover:-translate-y-0.5">
            Masuk
        </button>
    </form>

    <!-- Security Footer -->
    <div class="flex items-center justify-center gap-2 mt-8 pt-6 border-t border-border/50">
        <svg class="w-4 h-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
        </svg>
        <span class="text-xs text-secondary">Aman dan terenkripsi</span>
    </div>
</x-guest-layout>

<style>
    [x-cloak] { display: none !important; }
</style>
