<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Mata Kuliah') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-start mt-4">
                <x-secondary-button onclick="window.location='{{ route('dashboard') }}'">
                    {{ __('Kembali ke Dashboard') }}
                </x-secondary-button>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Manajemen Mata Kuliah</h3>
                        <a href="{{ route('courses.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800  border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700  focus:bg-gray-700 active:bg-gray-900  focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Tambah Mata Kuliah
                        </a>
                    </div>

                    @if ($courses->isEmpty())
                        <p>Belum ada mata kuliah yang terdaftar.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Mata Kuliah</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKS</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($courses as $course)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $course->course_code }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $course->course_name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $course->credits }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                {{-- Tautan Edit --}}
                                                <a href="{{ route('courses.edit', $course->id) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                                
                                                {{-- Form Hapus --}}
                                                <form action="{{ route('courses.destroy', $course->id) }}" method="POST" class="inline-block ml-2" onsubmit="confirmDelete(event)">
                                                  @csrf
                                                  @method('DELETE') {{-- Penting untuk operasi DELETE --}}
                                                  <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                                </form>
                                              </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(event) {
            event.preventDefault(); // Mencegah form langsung submit

            const form = event.target; // Dapatkan form yang memicu event ini

            Swal.fire({
                title: 'Apakah Anda Yakin?',
                text: "Anda tidak akan bisa mengembalikan ini!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33', // Warna merah untuk 'Ya, Hapus!'
                cancelButtonColor: '#6c757d', // Warna abu-abu untuk 'Batal'
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Jika pengguna mengklik 'Ya, Hapus!', submit form secara manual
                    form.submit();
                }
            });
        }
    </script>
    
</x-app-layout>