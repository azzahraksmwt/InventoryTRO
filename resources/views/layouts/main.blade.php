<!doctype html>
<html>
<style>
    .no-select {
        user-select: none;
    }
</style>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Untuk Sidebar --}}
    <script>
        function dropDown(elementId) {
            document.querySelector('#' + elementId).classList.toggle('hidden');
        }

        function rotateImage(elementId) {
            var image = document.getElementById(elementId);
            var currentRotation = image.style.transform.replace(/[^0-9]/g, ''); // Extract numeric value
            var newRotation = (currentRotation === '180' ? '0' : '180') + 'deg';
            image.style.transform = 'rotate(' + newRotation + ')';
        }
    </script>

    {{-- Untuk Auto isi Idtype nya berdasarkan jenisbarang yg dipilih --}}
    <script>
        function updateIdType() {
            var typeDropdown = document.getElementById('jenisbarang');
            var idTypeInput = document.getElementById('idtype');
            var selectedType = typeDropdown.value;

            // Mengatur ID TYPE otomatis berdasarkan pilihan 'Type'
            if (selectedType === 'material') {
                idTypeInput.value = 'M';
            } else if (selectedType === 'tool') {
                idTypeInput.value = 'T';
            }
        }
    </script>

    {{-- Untuk Tanggal --}}
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var currentDate = new Date().toISOString().split('T')[0];
                document.getElementById('modifiedbydate').value = currentDate;
            });
        </script>
    @endpush
    @stack('scripts')

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var currentDate = new Date().toISOString().split('T')[0];
                document.getElementById('tanggal_pinjam').value = currentDate;
            });
        </script>
    @endpush
    @stack('scripts')

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var currentDate = new Date().toISOString().split('T')[0];
                document.getElementById('tanggal_kembali').value = currentDate;
            });
        </script>
    @endpush
    @stack('scripts')

</head>
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito">

<body>
    <link rel="stylesheet" href="{{ asset('assets/css/iziToast.min.css') }}" />
    <script src="{{ asset('assets/js/iziToast.min.js') }}"></script>

    @if ($message = Session::get('success'))
        <script>
            iziToast.success({
                title: 'Successfully',
                position: 'bottomRight',
                message: '{{ $message }}',
            });
        </script>
    @endif

    @if ($message = Session::get('error'))
        <script>
            iziToast.error({
                title: 'Error',
                position: 'bottomRight',
                message: '{{ $message }}',
            });
        </script>
    @endif

    @if ($message = Session::get('info'))
        <script>
            iziToast.info({
                title: 'Hello {{ Auth::user()->namaPengguna ?? '' }}, Welcome to the INTRO!',
                position: 'bottomRight',
            });
        </script>
    @endif

    <style>
        .horizontal-fields {
            display: flex;
            flex-wrap: wrap;
        }

        .horizontal-fields>div {
            flex: 1;
            margin-right: 20px;
            /* Atur jarak antar elemen */
        }
    </style>

    <div class="flex flex-row">

        <div class="sidebar">
            @include('partials.sidebar')
        </div>

        <div class="container">
            @yield('container')
        </div>

    </div>
</body>

</html>
