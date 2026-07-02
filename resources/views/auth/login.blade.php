<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <div class="mb-4">
        <h2 class="text-xl lg:text-2xl font-bold text-slate-800">
            Selamat Datang.
        </h2>
        <p class="mt-1 text-sm text-slate-500">
            Masuk ke akun SIMAMI untuk mengelola proses Audit Mutu Internal.
        </p>
    </div>
    <form method="POST" action="{{ route('login') }}" class="space-y-3">
        @csrf
        {{-- EMAIL --}}
        <div>
            <label for="email" class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">
                Email
            </label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                autocomplete="username" placeholder="Masukkan email"
                class="w-full rounded-lg border-slate-300 focus:border-[#1E3A8A] focus:ring-[#1E3A8A]" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
        {{-- PASSWORD --}}
        <div>
            <label for="password" class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">
                Password
            </label>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                placeholder="Masukkan password"
                class="w-full rounded-lg border-slate-300 focus:border-[#1E3A8A] focus:ring-[#1E3A8A]" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>
        {{-- REMEMBER --}}
        <div class="flex items-center justify-between">
            <label class="inline-flex items-center gap-2">
                <input id="remember_me" type="checkbox" name="remember"
                    class="rounded border-slate-300 text-[#1E3A8A] focus:ring-[#1E3A8A]">
                <span class="text-sm text-slate-600">
                    Ingat Saya
                </span>
            </label>
        </div>
        {{-- BUTTON --}}
        <button type="submit"
            class="w-full rounded-lg bg-[#1E3A8A] py-3 text-sm font-semibold text-white transition hover:bg-[#152F79]">
            Masuk ke SIMAMI
        </button>
    </form>
</x-guest-layout>