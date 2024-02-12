@extends('layouts.main')
@section('container')
    <div class="mx-4 my-8">
        <h1 style="font-size: 30px; font: bold;" class="mb-6 no-select">Usage Info</h1>

        <form action="{{ route('usages.update', $usage->idpb) }}" method="POST" class="no-select" id="usageForm">
            @csrf
            @method('PUT')

            <input type="hidden" name="validasi_pengembalian" id="validasi_pengembalian" class="border border-gray-300 rounded w-full px-3 py-2" value="{{ Auth::user()->idPengguna }}" readonly>
            <input type="hidden" name="status_pengembalian" id="status_pengembalian" class="border border-gray-300 rounded w-full px-3 py-2" value="{{ $usage->status_pengembalian }}" readonly>

            <div class="horizontal-fields">
                <div class="mb-4">
                    <label for="idpb" class="block text-gray-700">Usage ID</label>
                    <div class="border border-gray-300 rounded px-3 py-2" readonly>{{ $usage->idpb }}</div>
                </div>

                <div class="mb-4">
                    <label for="idPengguna" class="block text-gray-700">Borrower</label>
                    <input type="text" name="namaPengguna" id="namaPengguna"
                        class="border border-gray-300 rounded w-full px-3 py-2" value="{{ $usage->user->namaPengguna }}"
                        readonly>
                    <input type="hidden" name="idPengguna" id="idPengguna" value="{{ $usage->idPengguna }}">
                </div>
            </div>

            <div class="horizontal-fields">
                <div class="mb-4">
                    <label for="idbarang" class="block text-gray-700">Name of Goods</label>
                    <input type="text" class="border border-gray-300 rounded w-full px-3 py-2"
                        value="{{ $usage->inventory->namabarang }}" readonly>
                    <input type="hidden" name="idbarang" value="{{ $usage->inventory->idbarang }}">
                </div>

                <div class="mb-4">
                    <label for="tanggal_pinjam" class="block text-gray-700">Borrowed Date</label>
                    <input type="date" name="tanggal_pinjam" id="tanggal_pinjam"
                        class="border border-gray-300 rounded w-full px-3 py-2" value="{{ $usage->tanggal_pinjam }}"
                        readonly>
                </div>
            </div>

            <div class="horizontal-fields">

                <div class="mb-4">
                    <label for="quantity_pinjam" class="block text-gray-700">Quantity Borrowed</label>
                    <input type="text" name="quantity_pinjam" id="quantity_pinjam"
                        class="border border-gray-300 rounded w-full px-3 py-2" value="{{ $usage->quantity_pinjam }}"
                        readonly>
                </div>

                <div class="mb-4">
                    <label for="kelas" class="block text-gray-700">Kelas</label>
                    <input type="text" name="kelas" id="kelas"
                        class="border border-gray-300 rounded w-full px-3 py-2" value="{{ $usage->user->kelas}} - {{ $usage->user->angkatan}}"
                        readonly>
                </div>
            </div>

            <div class="horizontal-fields">
                <div class="mb-4">
                    <label for="brg_rusak" class="block text-gray-700">Damaged or Lost Items</label>
                    <input type="text" name="brg_rusak" id="brg_rusak"
                        class="border border-gray-300 rounded w-full px-3 py-2" value="{{ $usage->brg_rusak }}" readonly>
                </div>

                <div class="mb-4">
                    <label for="idMatakuliah" class="block text-gray-700">Subject</label>
                    <input type="text" name="idMatakuliah" id="idMatakuliah"
                        class="border border-gray-300 rounded w-full px-3 py-2"
                        value="{{ $usage->subject->namaMatakuliah }}" readonly>
                    <input type="hidden" name="idMatakuliah" value="{{ $usage->subject->idMatakuliah }}">
                </div>
            </div>

            <div class="mb-4">
                <label for="foto" class="block text-gray-700">Proof of Return</label>
                <img src="{{ asset('fotobarang/' . $usage->foto) }}" alt="Proof of Return"
                    class="border border-gray-300 rounded w-full px-3 py-2" style="max-width: 300px; max-height: 200px;">
            </div>

            <a href="{{ route('usages.index_returnValidation') }}"
                class="bg-slate-700 text-white px-4 py-2 rounded-md">Cancel</a>
            <button type="button" onclick="terimaPemakaian()"
                class="bg-slate-700 text-white px-4 py-2 rounded-lg">Validasi</button>

        </form>

    </div>

    <script>
        function terimaPemakaian() {
            document.getElementById('status_pengembalian').value = 'Tervalidasi';
            // Mengirim formulir setelah mengubah nilai
            document.getElementById('usageForm').submit();
        }
    </script>

@endsection
