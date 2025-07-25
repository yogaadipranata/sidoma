<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Jadwal Kuliah') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-start mt-4 mb-4">
                <x-secondary-button onclick="window.location='{{ route('dosen.schedules_index') }}'">
                    {{ __('Kembali ke Daftar Jadwal') }}
                </x-secondary-button>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('dosen.schedules_store') }}">
                        @csrf

                        <div>
                            <x-input-label for="course_id" :value="__('Mata Kuliah')" />
                            <select id="course_id" name="course_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" required>
                                <option value="">-- Pilih Mata Kuliah --</option>
                                @foreach ($courses as $course)
                                    <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                        {{ $course->course_name }} ({{ $course->course_code }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('course_id')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="day" :value="__('Hari')" />
                            <select id="day" name="day" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" required>
                                <option value="">-- Pilih Hari --</option>
                                @foreach ($days as $day)
                                    <option value="{{ $day }}" {{ old('day') == $day ? 'selected' : '' }}>
                                        {{ $day }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('day')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="start_time" :value="__('Waktu Mulai')" />
                            <x-text-input id="start_time" class="block mt-1 w-full" type="time" name="start_time" :value="old('start_time')" required />
                            <x-input-error :messages="$errors->get('start_time')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="end_time" :value="__('Waktu Selesai')" />
                            <x-text-input id="end_time" class="block mt-1 w-full" type="time" name="end_time" :value="old('end_time')" required />
                            <x-input-error :messages="$errors->get('end_time')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="room" :value="__('Ruangan')" />
                            <x-text-input id="room" class="block mt-1 w-full" type="text" name="room" :value="old('room')" required />
                            <x-input-error :messages="$errors->get('room')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="academic_year" :value="__('Tahun Akademik')" />
                            <select id="academic_year" name="academic_year" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" required>
                                <option value="">-- Pilih Tahun Akademik --</option>
                                @foreach ($academicYears as $year)
                                    <option value="{{ $year }}" {{ old('academic_year') == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('academic_year')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="semester" :value="__('Semester')" />
                            <select id="semester" name="semester" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" required>
                                <option value="">-- Pilih Semester --</option>
                                @foreach ($semesters as $semester)
                                    <option value="{{ $semester }}" {{ old('semester') == $semester ? 'selected' : '' }}>
                                        {{ $semester }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('semester')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ms-4">
                                {{ __('Simpan Jadwal') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>