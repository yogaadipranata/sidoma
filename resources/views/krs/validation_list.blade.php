<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Validasi KRS Mahasiswa') }}
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
                    <h3 class="text-lg font-semibold mb-4">Daftar KRS Menunggu Persetujuan</h3>

                    @if ($pendingKrs->isEmpty())
                        <p>Tidak ada KRS mahasiswa yang menunggu persetujuan saat ini.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mahasiswa</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mata Kuliah</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKS</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tahun Akademik</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Semester</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($pendingKrs as $krs)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $krs->student->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $krs->course->course_name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $krs->course->credits }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $krs->academic_year }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $krs->semester }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                {{-- Form Setujui --}}
                                                <form action="{{ route('dosen.validate_krs', $krs->id) }}" method="POST" class="inline-block" onsubmit="confirmApprove(event)"> {{-- UBAH onsubmit --}}
                                                    @csrf
                                                    <input type="hidden" name="action" value="approve">
                                                    <button type="submit" class="text-green-600 hover:text-green-900">Setujui</button>
                                                </form>
                                                {{-- Tombol Tolak --}}
                                                <button type="button" onclick="rejectKrs({{ $krs->id }}, '{{ $krs->student->name }}', '{{ $krs->course->course_name }}')" class="text-red-600 hover:text-red-900 ml-2">Tolak</button>
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

    {{-- Script untuk SweetAlert2 Konfirmasi Hapus --}}
    <script>
        // Fungsi untuk konfirmasi Setujui
        function confirmApprove(event) {
            event.preventDefault(); // Mencegah form langsung submit
            const form = event.target; // Dapatkan form yang memicu event ini

            Swal.fire({
                title: 'Setujui KRS ini?',
                text: "KRS ini akan disetujui dan tidak bisa diubah statusnya menjadi menunggu lagi!",
                icon: 'question', // Atau 'info'
                showCancelButton: true,
                confirmButtonColor: '#28a745', // Warna hijau untuk 'Ya, Setujui!'
                cancelButtonColor: '#6c757d', // Warna abu-abu untuk 'Batal'
                confirmButtonText: 'Ya, Setujui!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // Jika pengguna mengklik 'Ya, Setujui!', submit form secara manual
                }
            });
        }

        // Fungsi untuk konfirmasi Tolak (Sudah ada dari sebelumnya)
        function rejectKrs(enrollmentId, studentName, courseName) {
            Swal.fire({
                title: 'Tolak KRS ini?',
                html: `Anda akan menolak KRS ${studentName} untuk mata kuliah ${courseName}.<br>Masukkan alasan penolakan (opsional):
                       <textarea id="rejection-reason" class="swal2-textarea" placeholder="Alasan penolakan"></textarea>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Tolak!',
                cancelButtonText: 'Batal',
                preConfirm: () => {
                    return document.getElementById('rejection-reason').value;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const rejectionReason = result.value;
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `{{ url('dosen/krs-validation') }}/${enrollmentId}`;
                    form.style.display = 'none';

                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    form.appendChild(csrfToken);

                    const actionInput = document.createElement('input');
                    actionInput.type = 'hidden';
                    actionInput.name = 'action';
                    actionInput.value = 'reject';
                    form.appendChild(actionInput);

                    const reasonInput = document.createElement('input');
                    reasonInput.type = 'hidden';
                    reasonInput.name = 'rejection_reason';
                    reasonInput.value = rejectionReason;
                    form.appendChild(reasonInput);

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
</x-app-layout>