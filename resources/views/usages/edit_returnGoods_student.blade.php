@extends('layouts.main')
@section('container')
<div class="mx-4 my-8">
    <h1 style="font-size: 30px; font: bold;" class="mb-6 no-select">Form Return of Goods</h1>

    <form action="{{ route('usages.update_student', $usage->idpb) }}" method="POST" class="no-select" id="usageForm" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <input type="hidden" name="idPengguna" id="idPengguna" class="border border-gray-300 rounded w-full px-3 py-2" value="{{ $usage->idPengguna }}" readonly>
        <input type="hidden" name="status_pengembalian" id="status_pengembalian" class="border border-gray-300 rounded w-full px-3 py-2" value="{{ $usage->status_pengembalian }}" readonly>

        <div class="mb-4">
            <label for="idpb" class="block text-gray-700">Usage ID</label>
            <div class="border border-gray-300 rounded px-3 py-2" readonly>{{ $usage->idpb }}</div>
        </div>        

        <div class="mb-4">
            <label for="idbarang" class="block text-gray-700">Name of Goods</label>
            <input type="text" class="border border-gray-300 rounded w-full px-3 py-2" value="{{ $usage->inventory->namabarang }}" readonly>
            <input type="hidden" name="idbarang" value="{{ $usage->inventory->idbarang }}">
        </div>

        <div class="mb-4">
            <label for="quantity_pinjam" class="block text-gray-700">Quantity Borrowed</label>
            <input type="text" name="quantity_pinjam" id="quantity_pinjam" class="border border-gray-300 rounded w-full px-3 py-2" value="{{ $usage->quantity_pinjam }}" readonly>
        </div>
              
        <div class="mb-4">
            <label for="tanggal_pinjam" class="block text-gray-700">Borrowed Date</label>
            <input type="date" name="tanggal_pinjam" id="tanggal_pinjam" class="border border-gray-300 rounded w-full px-3 py-2" value="{{ $usage->tanggal_pinjam }}" readonly>
        </div>

        <div class="mb-4">
            <label for="idMatakuliah" class="block text-gray-700">Subject</label>
            <input type="text" name="idMatakuliah" id="idMatakuliah" class="border border-gray-300 rounded w-full px-3 py-2" value="{{ $usage->subject->namaMatakuliah}}" readonly>
            <input type="hidden" name="idMatakuliah" value="{{ $usage->subject->idMatakuliah }}">
        </div>

        <div class="mb-4">
            <label for="brg_rusak" class="block text-gray-700">Damaged or Lost Items</label>
            <input type="number" name="brg_rusak" id="brg_rusak" class="border border-gray-300 rounded w-full px-3 py-2"
                min="0"
                max="{{ $usage->quantity_pinjam }}"
                value="0">
            <span id="quantityError" class="text-red-500"></span>
        </div>

        <div class="mb-4">
            <label for="foto" class="block text-gray-700">Proof of Return</label>
            <input type="file" name="foto" id="foto"  class="border border-gray-300 rounded w-full px-3 py-2">
        </div>

        <a href="{{ route('usages.index_student') }}" class="bg-slate-700 text-white px-4 py-2 rounded-md">Cancel</a>
        <button type="button" onclick="terimaPemakaian()" class="bg-slate-700 text-white px-4 py-2 rounded-lg">Submit</button>

    </form>

</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function terimaPemakaian() {
        var brgRusak = parseInt(document.getElementById('brg_rusak').value);
        var quantityPinjam = parseInt(document.getElementById('quantity_pinjam').value);

        if (brgRusak > quantityPinjam) {
            document.getElementById('quantityError').innerText = "damaged or lost items cannot exceed the borrowed items";
        } else {
            Swal.fire({
                text: "Make sure you fill it in honestly!",
                showCancelButton: true,
                reverseButtons: true,
                cancelButtonText: "Cancel",
                confirmButtonText: "Yes",
                confirmButtonColor: "#2B3467",
                cancelButtonColor: "#B80000",
                icon: "warning"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('status_pengembalian').value = 'Menunggu Validasi';
                    // Mengirim formulir setelah pengguna menekan "Yes"
                    document.getElementById('usageForm').submit();
                }
            });
        }
    }
</script>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Memberikan fokus ke input brg_rusak saat halaman dimuat
        document.getElementById('brg_rusak').focus();
    });
</script>



@endsection
