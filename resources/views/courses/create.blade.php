<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Mata Kuliah') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-start mt-4">
                <x-secondary-button onclick="window.location='{{ route('courses.index') }}'">
                    {{ __('Kembali ke Daftar') }}
                </x-secondary-button>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('courses.store') }}">
                        @csrf

                        <div>
                            <x-input-label for="course_code" :value="__('Kode Mata Kuliah')" />
                            <x-text-input id="course_code" class="block mt-1 w-full" type="text" name="course_code" :value="old('course_code')" required autofocus />
                            <x-input-error :messages="$errors->get('course_code')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="name" :value="__('Nama Mata Kuliah')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="course_name" :value="old('name')" required />
                            <x-input-error :messages="$errors->get('cours_name')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="credits" :value="__('SKS')" />
                            <x-text-input id="credits" class="block mt-1 w-full" type="number" name="credits" :value="old('credits')" required min="1" max="6" />
                            <x-input-error :messages="$errors->get('credits')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ms-4">
                                {{ __('Simpan Mata Kuliah') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>