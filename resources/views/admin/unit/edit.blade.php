<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Edit Unit
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow rounded-xl p-6">

                <form action="{{ route('admin.units.update', $unit) }}" method="POST">

                    @csrf
                    @method('PUT')

                    {{-- Nama Unit --}}
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Unit
                        </label>

                        <input type="text" name="nama" value="{{ old('nama', $unit->nama) }}"
                            class="w-full border rounded-lg px-4 py-2 focus:ring focus:ring-blue-200" required>
                    </div>

                    {{-- Jenis --}}
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Jenis Unit
                        </label>

                        <select name="jenis" class="w-full border rounded-lg px-4 py-2 focus:ring focus:ring-blue-200"
                            required>

                            <option value="PRODI" {{ $unit->jenis == 'PRODI' ? 'selected' : '' }}>
                                PRODI
                            </option>

                            <option value="LAB" {{ $unit->jenis == 'LAB' ? 'selected' : '' }}>
                                LAB
                            </option>

                        </select>
                    </div>

                    {{-- Lokasi --}}
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Lokasi
                        </label>

                        <input type="text" name="lokasi" value="{{ old('lokasi', $unit->lokasi) }}"
                            class="w-full border rounded-lg px-4 py-2 focus:ring focus:ring-blue-200">
                    </div>

                    {{-- Button --}}
                    <div class="flex justify-end gap-2">

                        <a href="{{ route('admin.units.index') }}" class="px-4 py-2 bg-gray-200 rounded-lg">
                            Batal
                        </a>

                        <button type="submit" class="px-4 py-2 bg-yellow-500 text-white rounded-lg">
                            Update
                        </button>

                    </div>

                </form>

            </div>

        </div>
    </div>
</x-app-layout>