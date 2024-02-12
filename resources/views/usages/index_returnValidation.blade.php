<!-- resources/views/usages/index_returnValidation.blade.php -->

@extends('layouts.main')
@section('container')
    <div class="mx-4 my-8">
        <h1 style="font-size:30px; font:bold;" class="mb-6 no-select">Return Validation</h1>

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
                    <th class="py-2 px-4 border-b">Status</th>
                    <th class="py-2 px-4 border-b">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($usages as $item)
                    <tr class="{{ $loop->odd ? 'bg-gray-200' : 'bg-gray-300' }}">
                        <td class="py-2 px-4 border-b text-center">{{ $item->idpb }}</td>
                        <td class="py-2 px-4 border-b text-center">{{ $item->inventory->namabarang }}</td>
                        <td class="py-2 px-4 border-b text-center">{{ $item->inventory->jenisbarang }}</td>
                        <td class="py-2 px-4 border-b text-center">{{ $item->tanggal_pinjam }}</td>
                        <td class="py-2 px-4 border-b text-center">{{ $item->quantity_pinjam }}</td>
                        <td class="py-2 px-4 border-b text-center">{{ $item->user->namaPengguna }}</td>
                        <td class="py-2 px-4 border-b text-center">{{ $item->subject->namaMatakuliah }}</td>
                        <td
                            class="py-2 px-4 border-b text-center
                                @if ($item->status_pengembalian == 'Menunggu Validasi') text-yellow-500
                                @elseif($item->status_pengembalian == 'Tervalidasi') text-green-500 @endif
                            ">
                            {{ $item->status_pengembalian }}
                        </td>
                        <td class="py-2 px-4 border-b text-center">
                            <a href="{{ route('usages.edit_returnValidation', $item->idpb) }}"
                                class="text-blue-500 underline italic">Click to Validation</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center">
                            <img src="{{ asset("foto/{$placeholderFoto}") }}" alt="Nothing" class="mx-auto w-128 h-96">
                            <p class="mt-2 text-gray-600">No items waiting for validation</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
