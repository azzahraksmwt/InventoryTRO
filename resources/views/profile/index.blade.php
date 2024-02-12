@extends('layouts.main')

@section('container')
    <div class="container mx-auto my-8 p-8 bg-white shadow-md rounded-md">
        <h1 class="text-3xl font-bold mb-6 text-center">User Profile</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="mb-4">
                <label class="block text-gray-600 font-bold mb-2">ID:</label>
                <p class="text-gray-800">{{ $user->idPengguna }}</p>
            </div>
            <div class="mb-4">
                <label class="block text-gray-600 font-bold mb-2">Name:</label>
                <p class="text-gray-800">{{ $user->namaPengguna }}</p>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-600 font-bold mb-2">Username:</label>
                <p class="text-gray-800">{{ $user->username }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-600 font-bold mb-2">Password:</label>
                <p class="text-gray-800">*********</p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-600 font-bold mb-2">Class:</label>
                <p class="text-gray-800">{{ $user->kelas }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-600 font-bold mb-2">Phone:</label>
                <p class="text-gray-800">{{ $user->nohp }}</p>
            </div>
        </div>

        <div class="mt-8 text-center">
            <a href="{{ route('profile.edit') }}" class="bg-slate-700 text-white px-4 py-2 rounded-full hover:bg-slate-800 transition duration-300">Edit Profile</a>
        </div>
    </div>
@endsection
