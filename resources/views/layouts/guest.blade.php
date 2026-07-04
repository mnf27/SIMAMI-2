<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="overscroll-none">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="font-sans antialiased bg-[#88A4F4]/50 lg:bg-gradient-to-br lg:from-[#1E3A8A] lg:to-[#080F24] overscroll-none overflow-y-hidden overflow-x-hidden">
    <div class="min-h-screen grid lg:grid-cols-2">
        {{-- LEFT SIDE --}}
        <div class="flex items-center hidden lg:flex relative text-white">
            <div class="absolute -bottom-24 -left-24 h-80 w-80 rounded-full bg-white/5 blur-3xl">
            </div>
            {{-- Blur Circle 2 --}}
            <div class="absolute top-20 right-20 h-56 w-56 rounded-full bg-white/5 blur-2xl">
            </div>
            {{-- Blur Circle 3 --}}
            <div class="absolute bottom-20 right-1/3 h-40 w-40 rounded-full bg-white/5 blur-2xl">
            </div>
            <div class="absolute inset-0 opacity-[0.04]"
                style="background-image: linear-gradient(white 1px, transparent 1px), linear-gradient(90deg, white 1px, transparent 1px); background-size: 40px 40px;">
            </div>
            <div class="relative flex flex-col justify-start pt-8 px-10">
                <div class="flex items-center gap-4 mb-8">
                    <x-login-logo class="w-15 h-15" />
                </div>
                <div class="mt-10">
                    <h2 class="text-5xl font-bold leading-tight max-w-xl">
                        Digitalisasi Sistem Informasi Audit Mutu Internal
                    </h2>
                    <p class="mt-4 text-blue-100 max-w-[1700px] mx-w-auto text-lg leading-relaxed">
                        Kelola proses audit, temuan, tindak lanjut, serta monitoring
                        mutu Program Studi dan Laboratorium secara terintegrasi.
                    </p>
                </div>
                {{-- Stats --}}
                <div class="grid grid-cols-3 gap-4 mt-16 max-w-xl">
                    <div class="rounded-2xl bg-white/10 backdrop-blur-sm p-4 shadow-lg">
                        <i data-lucide="clipboard-check" class="w-5 h-5 mb-3 text-white"></i>
                        <h3 class="font-bold text-white">
                            Audit
                        </h3>
                        <p class="text-sm text-blue-100">
                            Manajemen audit mutu internal
                        </p>
                    </div>
                    <div class="rounded-2xl bg-white/10 backdrop-blur-sm p-4 shadow-lg">
                        <i data-lucide="file-warning" class="w-5 h-5 mb-3 text-white"></i>
                        <h3 class="font-bold text-white">
                            Temuan
                        </h3>
                        <p class="text-sm text-blue-100">
                            Monitoring hasil audit
                        </p>
                    </div>
                    <div class="rounded-2xl bg-white/10 backdrop-blur-sm p-4 shadow-lg">
                        <i data-lucide="check-check" class="w-5 h-5 mb-3 text-white"></i>
                        <h3 class="font-bold text-white">
                            Tindak Lanjut
                        </h3>
                        <p class="text-sm text-blue-100">
                            Penyelesaian temuan audit
                        </p>
                    </div>
                </div>
            </div>
        </div>
        {{-- RIGHT SIDE --}}
        <div class="flex items-center justify-center py-4 px-4">
            <div class="w-full max-w-xl">
                {{-- MOBILE LOGO --}}
                <div class="lg:hidden flex justify-center mb-3">
                    <x-login-logo-mobile />
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white pt-4 pb-5 px-5 shadow-xl">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
    </script>
    <script>
        window.addEventListener('pageshow', function (event) {
            const navigation = performance.getEntriesByType('navigation')[0];
            
            if (event.persisted || navigation?.type === 'back_forward') {
                setTimeout(() => {
                    location.reload();
                }, 0);
            }
        });
    </script>
</body>

</html>