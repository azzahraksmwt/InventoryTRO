@extends('layouts.main')
@section('container')
    <div class="mx-4 my-8">
        <h1 style="font-size:30px; font:bold;" class="mb-6 no-select">Items Report</h1>

        <div class="flex items-center justify-between mb-4">
            <form action="{{ route('reports.index') }}" method="get" class="flex items-center space-x-2">
                @csrf
                <select name="jenis_barang_option" id="jenis_barang_option" class="border p-2 rounded-md">
                    <option selected disabled value="">--choose--</option>
                    <option value="Tools">Tool</option>
                    <option value="Materials">Material</option>
                    <option value="All">All</option>
                </select>
                <button type="submit" class="bg-slate-700 text-white px-4 py-2 rounded-md">Submit</button>
            </form>
        
            <a href="{{ route('reports.cetak') }}" class="bg-slate-700 text-white px-4 py-2 rounded-md">Cetak</a>
        </div>        

        <table class="w-full bg-white rounded-md overflow-hidden shadow-md no-select" style="font-size:15px; font:bold;">
            <thead class="bg-slate-700 text-white">
                <tr>
                    <th class="py-2 px-4 border-b font-bold">Usage ID</th>
                    <th class="py-2 px-4 border-b font-bold">Name of Goods</th>
                    <th class="py-2 px-4 border-b font-bold">Type</th>
                    <th class="py-2 px-4 border-b font-bold">Borrowed Date</th>
                    <th class="py-2 px-4 border-b font-bold">Borrowed QTY</th>
                    <th class="py-2 px-4 border-b font-bold">Student</th>
                    <th class="py-2 px-4 border-b font-bold">Class</th>
                    <th class="py-2 px-4 border-b font-bold">Generation</th>
                    <th class="py-2 px-4 border-b font-bold">Subject</th>
                    <th class="py-2 px-4 border-b font-bold">Usage Validator</th>
                    <th class="py-2 px-4 border-b font-bold">Return Validator</th>
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
                        <td class="py-2 px-4 border-b text-center">{{ $item->user->kelas }}</td>
                        <td class="py-2 px-4 border-b text-center">{{ $item->user->angkatan }}</td>
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
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
