@extends('layouts.main')

@section('container')
    <div class="container mx-auto my-8 p-8 bg-white shadow-md rounded-md">
        <h1 class="text-3xl font-bold mb-6 text-center">Edit Profile</h1>

        @if (session('success'))
            <div class="bg-green-200 p-4 rounded-md mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('profile.update', ['id' => $user->idPengguna]) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('post')
            <div class="mb-4">
                <label for="namaPengguna" class="block text-gray-700">Name</label>
                <input type="text" name="namaPengguna" id="namapengguna" value="{{ $user->namaPengguna }}" readonly
                    class="border border-gray-300 rounded w-full px-3 py-2" >
            </div>
            <div class="mb-4">
                <label for="nohp" class="block text-gray-700">Call Number</label>
                <input type="text" name="nohp" id="nohp" value="{{ $user->nohp }}"
                    class="border border-gray-300 rounded w-full px-3 py-2">
            </div>
            <div class="mb-4">
                <label for="username" class="block text-gray-700">Username</label>
                <input type="text" name="username" id="username" value="{{ $user->username }}"
                    class="border border-gray-300 rounded w-full px-3 py-2">
            </div>
            <div class="mb-4">
                <label for="password" class="block text-gray-700">Password</label>
                <input type="password" name="password" id="password"
                    class="border border-gray-300 rounded w-full px-3 py-2" required>
            </div>
            <div class="mb-4">
                <label for="foto" class="block text-gray-700">Change Image</label>
                <input type="file" name="foto" id="foto" class="border border-gray-300 rounded w-full px-3 py-2">
            </div>

            <input name="role" type="hidden" name="role" id="role" value="{{ $user->role }}" readonly>
            <input name="idPengguna" type="hidden" id="idPengguna" value="{{ $user->idPengguna }}" readonly>

            <div class="mb-4">
                <button type="submit" class="bg-slate-700 text-white px-4 py-2 rounded-full">Update Profile</button>
            </div>
        </form>
    </div>
@endsection
