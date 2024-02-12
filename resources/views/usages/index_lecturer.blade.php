@extends('layouts.main')
@section('container')
    <div class="mx-4 my-8">
        <h1 style="font-size:30px; font:bold;" class="mb-6 no-select">Usage History</h1>
       
        <form id="clearHistoryForm" class="text-end" action="{{ route('usages.truncate_lecturer') }}" method="post">
            @csrf
            @method('DELETE')
            <button type="button" onclick="clearHistoryWithConfirmation(event)" class="bg-red-800 text-white px-4 py-2 rounded-md mb-2">Clear All History</button>
        </form>

        <table class="w-full bg-white rounded-md overflow-hidden shadow-md no-select" style="font-size:15px; font:bold;">
            <thead class="bg-slate-700 text-white">
                <tr>
                    <th class="py-2 px-4 border-b">Usage ID</th>
                    <th class="py-2 px-4 border-b">Name of Goods</th>
                    <th class="py-2 px-4 border-b">Type</th>
                    <th class="py-2 px-4 border-b">Borrowed Date</th>
                    <th class="py-2 px-4 border-b">Borrowed QTY</th>
                    <th class="py-2 px-4 border-b">Student</th>
                    <th class="py-2 px-4 border-b">Subject</th>
                    <th class="py-2 px-4 border-b">Usage Validator</th>
                    <th class="py-2 px-4 border-b">Return Validator</th>
                    <th class="py-2 px-4 border-b">Usage Status</th>
                    <th class="py-2 px-4 border-b">Return Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($usages as $item)
                    <tr class="{{ $loop->odd ? 'bg-gray-200' : 'bg-gray-300' }}">
                        <td class="py-2 px-4 border-b text-center">{{ $item->idpb }}</td>
                        <td class="py-2 px-4 border-b text-center">{{ $item->inventory->namabarang }}</td>
                        <td class="py-2 px-4 border-b text-center">{{ $item->inventory->jenisbarang }}</td>
                        <td class="py-2 px-4 border-b text-center">{{ $item->tanggal_pinjam }}</td>
                        <td class="py-2 px-4 border-b text-center">{{ $item->quantity_pinjam }}</td>
                        <td class="py-2 px-4 border-b text-center">{{ $item->user->namaPengguna }}</td>
                        <td class="py-2 px-4 border-b text-center">{{ $item->subject->namaMatakuliah }}</td>
                        <td class="py-2 px-4 border-b text-center">
                            @if ($item->validator)
                                {{ $item->validator->namaPengguna }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="py-2 px-4 border-b text-center">
                            @if ($item->returnValidator)
                                {{ $item->returnValidator->namaPengguna }}
                            @else
                                -
                            @endif
                        </td>
                        <td
                            class="py-2 px-4 border-b text-center
                                    @if ($item->status_pemakaian == 'Menunggu Validasi') text-yellow-500
                                    @elseif($item->status_pemakaian == 'Ditolak') text-red-500
                                    @elseif($item->status_pemakaian == 'Tervalidasi') text-green-500 @endif
                                ">
                            {{ $item->status_pemakaian }}</td>
                        <td
                            class="py-2 px-4 border-b text-center
                            @if ($item->status_pengembalian == 'Menunggu Validasi') text-yellow-500
                            @elseif($item->status_pengembalian == 'Tervalidasi')
                                text-green-500 
                            @elseif($item->status_pengembalian == null)
                                - 
                            @endif 
                            ">
                            {{ $item->status_pengembalian ?? '-' }}
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function clearHistoryWithConfirmation(event) {
            // Hentikan pengiriman formulir secara default
            event.preventDefault();

            // Tampilkan konfirmasi SweetAlert
            Swal.fire({
                title: "Clear All History?",
                text: "Make sure all items of the 'tool' type have been returned, for data accuracy!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#B80000",
                cancelButtonColor: "#2B3467",
                confirmButtonText: "Yes, clear it!",
            }).then((result) => {
                // Jika pengguna menekan tombol "Yes"
                if (result.isConfirmed) {
                    // Submit formulir clear history setelah konfirmasi
                    document.getElementById('clearHistoryForm').submit();
                }
            });
        }
    </script>
@endsection
