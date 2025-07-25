<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Jadwal Kuliah') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-start mt-4">
                <x-secondary-button onclick="window.location='{{ route('dosen.schedules_index') }}'">
                    {{ __('Kembali ke Daftar Jadwal') }}
                </x-secondary-button>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('dosen.schedules_update', $schedule->id) }}">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-input-label for="course_id" :value="__('Mata Kuliah')" />
                            <select id="course_id" name="course_id" class="border-gray-300 bg-gray-100 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" disabled>
                                <option value="{{ $schedule->course->id }}" selected>
                                    {{ $schedule->course->course_name }} ({{ $schedule->course->course_code }})
                                </option>
                            </select>
                            {{-- Hidden input untuk mengirim course_id karena select disabled --}}
                            <input type="hidden" name="course_id" value="{{ $schedule->course->id }}">
                        </div>

                        <div class="mt-4">
                            <x-input-label for="day" :value="__('Hari')" />
                            <select id="day" name="day" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" required>
                                <option value="">-- Pilih Hari --</option>
                                @foreach ($days as $day)
                                    <option value="{{ $day }}" {{ old('day', $schedule->day) == $day ? 'selected' : '' }}>
                                        {{ $day }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('day')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="start_time" :value="__('Waktu Mulai')" />
                            <x-text-input id="start_time" class="block mt-1 w-full" type="time" name="start_time" :value="old('start_time', \Carbon\Carbon::parse($schedule->start_time)->format('H:i'))" required />
                            <x-input-error :messages="$errors->get('start_time')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="end_time" :value="__('Waktu Selesai')" />
                            <x-text-input id="end_time" class="block mt-1 w-full" type="time" name="end_time" :value="old('end_time', \Carbon\Carbon::parse($schedule->end_time)->format('H:i'))" required />
                            <x-input-error :messages="$errors->get('end_time')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="room" :value="__('Ruangan')" />
                            <x-text-input id="room" class="block mt-1 w-full" type="text" name="room" :value="old('room', $schedule->room)" required />
                            <x-input-error :messages="$errors->get('room')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="academic_year" :value="__('Tahun Akademik')" />
                            <select id="academic_year" name="academic_year" class="border-gray-300 bg-gray-100 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" disabled>
                                <option value="{{ $schedule->academic_year }}" selected>{{ $schedule->academic_year }}</option>
                            </select>
                            <input type="hidden" name="academic_year" value="{{ $schedule->academic_year }}">
                        </div>

                        <div class="mt-4">
                            <x-input-label for="semester" :value="__('Semester')" />
                            <select id="semester" name="semester" class="border-gray-300 bg-gray-100 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" disabled>
                                <option value="{{ $schedule->semester }}" selected>{{ $schedule->semester }}</option>
                            </select>
                            <input type="hidden" name="semester" value="{{ $schedule->semester }}">
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ms-4">
                                {{ __('Update Jadwal') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>