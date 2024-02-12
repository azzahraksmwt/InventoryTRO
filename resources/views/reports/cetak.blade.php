<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        @media print {
            table th {
                font-weight: bold;
            }
    
            /* Hapus atau ganti kelas yang menentukan warna latar belakang pada elemen <tr> */
            table tbody tr {
                background-color: transparent !important;
            }
    
            /* Menengahkan teks pada elemen <h1> saat mencetak */
            h1 {
                text-align: center;
            }
    
            /* Membuat teks pada elemen <thead> saat mencetak menjadi bold */
            table thead th {
                font-weight: bold;
            }
        }
    </style>
    
</head>

<body>
    <h1 style="font-size: 24px; font-weight: bold; margin-bottom: 20px;">Item Reports</h1>
    <table class="w-full bg-white rounded-md overflow-hidden shadow-md no-select" style="font-size:15px; font:bold;">
        <thead class="bg-slate-700 text-black border-b">
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
                <tr>
                    <td class="py-2 px-4 text-center">{{ $item->idpb }}</td>
                    <td class="py-2 px-4 text-center">{{ $item->inventory->namabarang }}</td>
                    <td class="py-2 px-4 text-center">{{ $item->inventory->jenisbarang }}</td>
                    <td class="py-2 px-4 text-center">{{ $item->tanggal_pinjam }}</td>
                    <td class="py-2 px-4 text-center">{{ $item->quantity_pinjam }}</td>
                    <td class="py-2 px-4 text-center">{{ $item->user->namaPengguna }}</td>
                    <td class="py-2 px-4 text-center">{{ $item->user->kelas }}</td>
                    <td class="py-2 px-4 text-center">{{ $item->user->angkatan }}</td>
                    <td class="py-2 px-4 text-center">{{ $item->subject->namaMatakuliah }}</td>
                    <td class="py-2 px-4 text-center">
                        @if ($item->validator)
                            {{ $item->validator->namaPengguna }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="py-2 px-4 text-center">
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
    <script>
        // For automatic printing when the page loads
        window.onload = function() {
            window.print();
        }
    </script>
</body>

</html>
