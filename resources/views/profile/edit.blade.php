<x-app-layout>
    <x-slot name="header">
    </x-slot>

    <div class="pt-[15px] pb-[13px] lg:py-[14px]">
        <div class="max-w-[1700px] mx-auto space-y-2">
            <div
                class="relative overflow-hidden rounded-xl bg-gradient-to-br from-[#1E3A8A] to-[#4866BD] pt-3 pb-4 px-4 lg:pl-5 lg:py-4 text-white shadow-md mb-3">

                <div class="absolute right-0 top-0 opacity-10">
                    <i data-lucide="user-circle-2" class="w-40 h-40"></i>
                </div>

                <div class="relative flex flex-col lg:flex-row lg:justify-between gap-2">

                    <div>
                        <h1 class="text-2xl font-bold">
                            Profil Saya
                        </h1>

                        <p class="text-blue-100 max-w-xl text-sm">
                            Kelola informasi akun, keamanan, dan pengaturan profil SIMAMI.
                        </p>
                    </div>

                </div>

            </div>
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>