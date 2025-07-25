<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kartu Rencana Studi (KRS)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-start mb-4"> {{-- mb-4 untuk margin bawah --}}
                <x-secondary-button onclick="window.location='{{ route('dashboard') }}'">
                    {{ __('Kembali ke Dashboard') }}
                </x-secondary-button>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Pengisian KRS Semester {{ $currentSemester }} Tahun Akademik {{ $currentAcademicYear }}</h3>

                    <form method="POST" action="{{ route('mahasiswa.store_krs') }}">
                        @csrf

                        <h4 class="text-md font-semibold mb-2">Pilih Mata Kuliah:</h4>
                        @if ($availableCourses->isEmpty())
                            <p>Belum ada mata kuliah yang tersedia.</p>
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach ($availableCourses as $course)
                                    <div class="flex items-center">
                                        @php
                                            // Cari pendaftaran mata kuliah ini oleh mahasiswa
                                            $currentEnrollment = $enrolledCourses->where('course_id', $course->id)->first();
                                            // Cek apakah sudah disetujui
                                            $isDisabled = $currentEnrollment && $currentEnrollment->is_approved;
                                        @endphp
                                        <input type="checkbox" id="course_{{ $course->id }}" name="selected_courses[]" value="{{ $course->id }}"
                                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                            {{ $currentEnrollment ? 'checked' : '' }} {{-- Centang jika sudah terdaftar --}}
                                            {{ $isDisabled ? 'disabled' : '' }}> {{-- Nonaktifkan jika sudah disetujui --}}
                                        <label for="course_{{ $course->id }}" class="ml-2 text-sm text-gray-700">
                                            {{ $course->course_name }} ({{ $course->credits }} SKS) - {{ $course->course_code }}
                                            @if ($isDisabled)
                                                <span class="text-green-600 text-xs">(Disetujui)</span>
                                            @elseif ($currentEnrollment && !$currentEnrollment->is_approved && $currentEnrollment->rejection_reason)
                                                 <span class="text-red-600 text-xs">(Ditolak)</span>
                                            @elseif ($currentEnrollment)
                                                 <span class="text-yellow-600 text-xs">(Menunggu)</span>
                                            @endif
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            <x-input-error :messages="$errors->get('selected_courses')" class="mt-2" />
                        @endif

                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button class="ms-4">
                                {{ __('Simpan KRS') }}
                            </x-primary-button>
                        </div>
                    </form>

                    <h4 class="text-md font-semibold mt-8 mb-2">Mata Kuliah yang Sudah Diambil (Semester Ini):</h4>
                    @if ($enrolledCourses->isEmpty())
                        <p>Belum ada mata kuliah yang Anda daftarkan untuk semester ini.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Mata Kuliah</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKS</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th> {{-- TAMBAH INI --}}
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($enrolledCourses as $enrollment)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $enrollment->course->course_code }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $enrollment->course->course_name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $enrollment->course->credits }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            @if ($enrollment->is_approved)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Disetujui
                                                </span>
                                            @elseif (!is_null($enrollment->rejection_reason))
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Ditolak
                                                </span>
                                                <br>
                                                <small class="text-gray-600">{{ $enrollment->rejection_reason }}</small>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    Menunggu
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            {{-- Tombol Hapus hanya jika Ditolak atau Menunggu --}}
                                            @if (!$enrollment->is_approved) {{-- Belum disetujui --}}
                                                <form action="{{ route('mahasiswa.delete_krs', $enrollment->id) }}" method="POST" class="inline-block" onsubmit="confirmMahasiswaDeleteKrs(event, '{{ $enrollment->course->course_name }}')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

{{-- Script untuk SweetAlert2 Konfirmasi Hapus KRS oleh Mahasiswa --}}
<script>
    function confirmMahasiswaDeleteKrs(event, courseName) {
        event.preventDefault();
        const form = event.target;

        Swal.fire({
            title: 'Hapus KRS ini?',
            text: `Anda akan menghapus mata kuliah ${courseName} dari pengajuan KRS Anda.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    }
</script>
</x-app-layout>