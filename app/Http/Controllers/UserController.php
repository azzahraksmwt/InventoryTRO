<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    //controller untuk Lectures
    public function index_lecturers()
    {
        $users = User::all();
        return view('users.index_lecturers', compact('users'));
    }

    public function create_lecturers()
    {
        $adminData = User::getAdmin();
        return view('users.create_lecturers', compact('adminData'));
    }


    public function store_lecturers(Request $request)
    {
        $request->validate([
            'idPengguna' => 'required|string|max:50',
            'namaPengguna' => 'required|string|max:50',
            'kelas' => 'nullable|string|max:50',
            'nohp' => 'nullable|string|max:20',
            'angkatan' => 'nullable|integer',
            'username' => 'required|string|max:50',
            'password' => 'required|string|max:255', // Hapus bcrypt() di sini
            'admin' => 'nullable|string|max:50',
            'role' => 'required|string|max:225',
        ]);

        // Hash password sebelum menyimpan ke database
        $hashedPassword = bcrypt($request->input('password'));

        // Tambahkan hashed password ke data request
        $data = $request->all();
        $data['password'] = $hashedPassword;

        User::create($data);

        return redirect()->route('users.index_lecturers')->with('success', 'adding lecturer data');
    }

    public function edit_lecturers($id)
    {
        $users = User::findOrFail($id);
        $adminData = User::getAdmin();
        return view('users.edit_lecturers', compact('users', 'adminData'));
    }

    public function update_lecturers(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'idPengguna' => 'required|string|max:50',
            'namaPengguna' => 'required|string|max:50',
            'kelas' => 'nullable|string|max:50',
            'nohp' => 'nullable|string|max:20',
            'angkatan' => 'nullable|integer',
            'username' => 'required|string|max:50',
            'password' => 'required|string|max:255', // Hapus bcrypt() di sini
            'admin' => 'nullable|string|max:50',
            'role' => 'required|string|max:225',
        ]);
        $data = $request->all();
        $hashedPassword = bcrypt($request->input('password'));
        $data['password'] = $hashedPassword;
        $user->update($data);
        return redirect()->route('users.index_lecturers')->with('success', 'update lecturer data');
    }

    public function show_lecturers($id)
    {
        $user = User::findOrFail($id);
        return view('users.show_lecturers', compact('user'));
    }

    public function destroy_lecturers($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return redirect()->route('users.index_lecturers')->with('success', 'Delete lecturer data');
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1451) {
                // Integrity constraint violation (foreign key constraint fails)
                return redirect()->route('users.index_lecturers')->with('error', 'you have to clear all history to delete lecturer');
            } else {
                // Other database-related errors
                return redirect()->route('users.index_lecturers')->with('error', 'Error deleting the lecturer data.');
            }
        }
    }


    //controller unutuk students
    public function index_students()
    {
        $users = User::all();
        return view('users.index_students', compact('users'));
    }

    public function create_students()
    {
        $adminData = User::getAdmin();
        return view('users.create_students', compact('adminData'));
    }

    public function store_students(Request $request)
    {
        $request->validate([
            'idPengguna' => 'required|string|max:50',
            'namaPengguna' => 'required|string|max:50',
            'kelas' => 'nullable|string|max:50',
            'nohp' => 'nullable|string|max:20',
            'angkatan' => 'nullable|integer',
            'username' => 'required|string|max:50',
            'password' => 'required|string|max:255', // Hapus bcrypt() di sini
            'admin' => 'nullable|string|max:50',
            'role' => 'required|string|max:225',
        ]);

        // Hash password sebelum menyimpan ke database
        $hashedPassword = bcrypt($request->input('password'));

        // Tambahkan hashed password ke data request
        $data = $request->all();
        $data['password'] = $hashedPassword;

        User::create($data);

        return redirect()->route('users.index_students')->with('success', 'adding student data');
    }

    public function edit_students($id)
    {
        $users = User::findOrFail($id);
        $adminData = User::getAdmin();
        return view('users.edit_students', compact('users', 'adminData'));
    }

    public function update_students(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'idPengguna' => 'required|string|max:50',
            'namaPengguna' => 'required|string|max:50',
            'kelas' => 'nullable|string|max:50',
            'nohp' => 'nullable|string|max:20',
            'angkatan' => 'nullable|integer',
            'username' => 'required|string|max:50',
            'password' => 'required|string|max:255', // Hapus bcrypt() di sini
            'admin' => 'nullable|string|max:50',
            'role' => 'required|string|max:225',
        ]);
        $data = $request->all();
        $hashedPassword = bcrypt($request->input('password'));
        $data['password'] = $hashedPassword;
        $user->update($data);
        return redirect()->route('users.index_students')->with('success', 'update student data');
    }

    public function show_students($id)
    {
        $user = User::findOrFail($id);
        return view('users.show_students', compact('user'));
    }

    public function destroy_students($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return redirect()->route('users.index_students')->with('success', 'Delete student data');
        } catch (QueryException $e) {
            // Mengatur pesan error jika ada foreign key constraint
            return redirect()->route('users.index_students')->with('error', 'you have to clear all history to delete student');
        }
    }

    public function indexProfile()
    {
        $user = Auth::user();
        return view('profile.index', compact('user'));
    }

    public function editProfile()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }
    
    public function updateProfile(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'idPengguna' => 'required|string|max:50',
            'namaPengguna' => 'required|string|max:50',
            'kelas' => 'nullable|string|max:50',
            'nohp' => 'nullable|string|max:20',
            'foto' => 'nullable|file|image|mimes:jpeg,png,jpg,svg|max:3072',
            'angkatan' => 'nullable|integer',
            'username' => 'required|string|max:50',
            'password' => 'required|string|max:255', // Hapus bcrypt() di sini
            'admin' => 'nullable|string|max:50',
            'role' => 'required|string|max:225',
        ]);

        $data = $request->all();

        // Check if there is a new photo to upload
        if ($request->hasFile('foto')) {
            // Delete the old photo if it exists
            if ($user->foto && Storage::exists("fotoprofil/{$user->foto}")) {
                Storage::delete("fotoprofil/{$user->foto}");
            }

            // Upload the new photo
            $fotoPath = $request->file('foto')->store('/fotoprofil');
            $data['foto'] = basename($fotoPath);
        }
        // Hash the password
        $hashedPassword = Hash::make($request->input('password'));
        $data['password'] = $hashedPassword;

        $user->update($data);

        return redirect()->route('home')->with('success', 'Profile updated successfully.');
    }
}
