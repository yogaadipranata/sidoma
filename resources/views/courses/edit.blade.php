<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Mata Kuliah') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-start mt-4">
                <x-secondary-button onclick="window.location='{{ route('courses.index') }}'">
                    {{ __('Kembali ke Daftar Mata Kuliah') }}
                </x-secondary-button>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{-- Perhatikan method POST dan @method('PUT') --}}
                    <form method="POST" action="{{ route('courses.update', $course) }}">
                        @csrf
                        @method('PUT') {{-- Penting untuk operasi UPDATE --}}

                        <div>
                            <x-input-label for="course_code" :value="__('Kode Mata Kuliah')" />
                            {{-- Value diisi dari data $course yang ada --}}
                            <x-text-input id="course_code" class="block mt-1 w-full" type="text" name="course_code" :value="old('course_code', $course->course_code)" required autofocus />
                            <x-input-error :messages="$errors->get('course_code')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="course_name" :value="__('Nama Mata Kuliah')" />
                            {{-- Value diisi dari data $course yang ada --}}
                            <x-text-input id="course_name" class="block mt-1 w-full" type="text" name="course_name" :value="old('course_name', $course->course_name)" required />
                            <x-input-error :messages="$errors->get('course_name')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="credits" :value="__('SKS')" />
                            {{-- Value diisi dari data $course yang ada --}}
                            <x-text-input id="credits" class="block mt-1 w-full" type="number" name="credits" :value="old('credits', $course->credits)" required min="1" max="6" />
                            <x-input-error :messages="$errors->get('credits')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ms-4">
                                {{ __('Update Mata Kuliah') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>