<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Tambah Unit
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow rounded-xl p-6">

                <form action="{{ route('admin.units.store') }}" method="POST">

                    @csrf

                    {{-- Nama Unit --}}
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Unit
                        </label>

                        <input type="text" name="nama" value="{{ old('nama') }}"
                            class="w-full border rounded-lg px-4 py-2 focus:ring focus:ring-blue-200" required>
                    </div>

                    {{-- Jenis --}}
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Jenis Unit
                        </label>

                        <select name="jenis" class="w-full border rounded-lg px-4 py-2 focus:ring focus:ring-blue-200"
                            required>

                            <option value="">
                                -- Pilih Jenis --
                            </option>

                            <option value="PRODI">
                                PRODI
                            </option>

                            <option value="LAB">
                                LAB
                            </option>

                        </select>
                    </div>

                    {{-- Lokasi --}}
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Lokasi
                        </label>

                        <input type="text" name="lokasi" value="{{ old('lokasi') }}"
                            class="w-full border rounded-lg px-4 py-2 focus:ring focus:ring-blue-200">
                    </div>

                    {{-- Button --}}
                    <div class="flex justify-end gap-2">

                        <a href="{{ route('admin.units.index') }}" class="px-4 py-2 bg-gray-200 rounded-lg">
                            Batal
                        </a>

                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                            Simpan
                        </button>

                    </div>

                </form>

            </div>

        </div>
    </div>
</x-app-layout>