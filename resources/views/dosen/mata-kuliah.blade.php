<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Mata Kuliah (Dosen)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold">Ini adalah halaman daftar mata kuliah khusus Dosen.</h3>
                    <p>Di sini Dosen bisa melihat dan mengelola mata kuliah yang diajarkan.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>