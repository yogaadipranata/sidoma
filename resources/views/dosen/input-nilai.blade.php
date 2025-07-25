<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Input Nilai Mahasiswa (Dosen)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold">Ini adalah halaman input nilai khusus Dosen.</h3>
                    <p>Dosen bisa memasukkan atau mengubah nilai mahasiswa di sini.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>