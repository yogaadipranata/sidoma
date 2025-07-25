<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{-- Cek role user yang sedang login --}}
                    @if (Auth::user()->role === 'dosen')
                        {{-- Tampilan khusus untuk Dosen --}}
                        <h3 class="text-lg font-semibold mb-4">Selamat Datang, {{ Auth::user()->name }}</h3>
                        <p>Anda telah login sebagai <b>Dosen</b>. Di sini Anda bisa mengelola mata kuliah dan input nilai mahasiswa.</p>

                        <div class="mt-6">
                            <h4 class="text-md font-semibold mb-2">Menu Dosen:</h4>
                            <ul class="list-disc pl-5">
                              <li><a href="{{ route('courses.index') }}" class="text-blue-600 hover:underline">Daftar Mata Kuliah</a></li>
                              <li><a href="{{ route('dosen.schedules_index') }}" class="text-blue-600 hover:underline">Manajemen Jadwal Kuliah</a></li>
                              <li><a href="{{ route('dosen.krs_validation') }}" class="text-blue-600 hover:underline">Validasi KRS Mahasiswa</a></li>
                              <li><a href="{{ route('dosen.input_nilai') }}" class="text-blue-600 hover:underline">Input Nilai Mahasiswa</a></li>
                            </ul>
                        </div>

                    @elseif (Auth::user()->role === 'mahasiswa')
                        {{-- Tampilan khusus untuk Mahasiswa --}}
                        <h3 class="text-lg font-semibold mb-4">Selamat Datang, {{ Auth::user()->name }}</h3>
                        <p>Anda telah login sebagai <b>Mahasiswa</b>. Di sini Anda bisa melihat jadwal mata kuliah dan transkrip nilai Anda.</p>

                        <div class="mt-6">
                            <h4 class="text-md font-semibold mb-2">Menu Mahasiswa:</h4>
                            <ul class="list-disc pl-5">
                              <li><a href="{{ route('mahasiswa.krs_form') }}" class="text-blue-600 hover:underline">Kartu Rencana Studi (KRS)</a></li>
                              <li><a href="{{ route('mahasiswa.jadwal') }}" class="text-blue-600 hover:underline">Lihat Jadwal Kuliah</a></li>
                              <li><a href="{{ route('mahasiswa.transkrip') }}" class="text-blue-600 hover:underline">Lihat Transkrip Nilai</a></li>
                            </ul>
                        </div>

                    @else
                        {{-- Tampilan default jika role tidak dikenali (opsional, sebagai fallback) --}}
                        <h3 class="text-lg font-semibold mb-4">Selamat Datang, **{{ Auth::user()->name }}**!</h3>
                        <p>Anda telah login. Role Anda tidak dikenali dalam sistem ini.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
