<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Usage;
use App\Models\User;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class UsageController extends Controller
{

    // BUAT HISTORY
    public function index()
    {
        $usages = Usage::with('validator')->orderBy('created_at', 'desc')->get();
        $inventorys = Inventory::all();
        return view('usages.index', compact('usages', 'inventorys'));
    }
    

    //View Cetak
    public function cetak_laporan()
    {
        $inventorys = Inventory::all();
        $usages = Usage::all();
        $usages = Usage::with('validator')->get();
        return view('reports.cetak', compact('usages'));
    }

    //BUAT NAMPILIN TABEL BERDASRKAN OPSI JENIS BARANG     
    public function index_report(Request $request)
    {
        // Get the selected option from the request
        $selectedOption = $request->input('jenis_barang_option');

        // Start building the query
        $query = Usage::query();

        // Join the inventory table to access the jenisbarang field
        $query->join('inventorys', 'usages.idbarang', '=', 'inventorys.idbarang');

        // Join the users table to access the idPengguna field
        $query->join('users', 'usages.idPengguna', '=', 'users.idPengguna');

        if ($selectedOption == 'Tools') {
            $query->where('inventorys.jenisbarang', 'tool');
            $query->where('status_pemakaian', 'Tervalidasi');
            $query->where('status_pengembalian', 'Tervalidasi');
        } elseif ($selectedOption == 'Materials') {
            $query->where('inventorys.jenisbarang', 'material');
            $query->where('status_pemakaian', 'Tervalidasi');
        } elseif ($selectedOption == 'All') {
            $query->where(function ($q) {
                $q->where('inventorys.jenisbarang', 'tool')
                    ->where('status_pemakaian', 'Tervalidasi')
                    ->where('status_pengembalian', 'Tervalidasi');
            })->orWhere(function ($q) {
                $q->where('inventorys.jenisbarang', 'material')
                    ->where('status_pemakaian', 'Tervalidasi');
            });
            $query->orderBy('usages.idpb', 'asc');
        }

        $usages = $query->get();

        return view('reports.index', compact('usages'));
    }

    public function create()
    {
        $inventorys = Inventory::all();
        $subjects = Subject::all(); // Pastikan untuk mendapatkan data subjek juga
        return view('usages.create', compact('inventorys', 'subjects'));
    }

    public function store(Request $request)
    {
        // Validasi input dari form
        $request->validate([
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'nullable|date',
            'quantity_pinjam' => 'required|integer',
            'quantity_kembali' => 'nullable|integer',
            'kondisi_barang' => 'nullable|string|max:50',
            'status_pemakaian' => 'nullable|string|max:50',
            'status_pengembalian' => 'nullable|string|max:50',
            'validasi_pemakaian' => 'nullable|string|max:50',
            'validasi_pengembalian' => 'nullable|string|max:50',
            'brg_rusak' => 'nullable|integer',
            'foto' => 'nullable|string|image|mimes:jpeg,png,jpg,svg|max:3072',
            'idPengguna' => 'required|string|max:50',
            'idbarang' => 'required|integer',
            'idMatakuliah' => 'required|string|max:50',
        ]);

        // Simpan data penggunaan ke database
        Usage::create($request->all());

        return redirect()->route('usages.create')
            ->with('success', 'submitted the form');
    }

    public function show(Usage $usage)
    {
        // Tampilkan halaman detail untuk penggunaan tertentu
        return view('usages.show', compact('usage'));
    }

    public function edit(Usage $usage)
    {
        $inventorys = Inventory::all();
        $subjects = Subject::all();

        return view('usages.edit', compact('usage', 'inventorys', 'subjects'));
    }

    public function update(Request $request, Usage $usage)
    {
        // Validasi input dari form
        $request->validate([
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'nullable|date',
            'quantity_pinjam' => 'required|integer',
            'quantity_kembali' => 'nullable|integer',
            'kondisi_barang' => 'nullable|string|max:50',
            'status_pemakaian' => 'nullable|string|max:50',
            'status_pengembalian' => 'nullable|string|max:50',
            'validasi_pemakaian' => 'nullable|string|max:50',
            'validasi_pengembalian' => 'nullable|string|max:50',
            'brg_rusak' => 'nullable|integer',
            'foto' => 'nullable|file|image|mimes:jpeg,png,jpg,svg|max:3072',
            'idPengguna' => 'required|string|max:50',
            'idbarang' => 'required|integer',
            'idMatakuliah' => 'required|string|max:50',
        ]);

        // Perbarui data penggunaan di database
        $usage->update($request->all());

        // Periksa apakah ada file foto yang diunggah
        if ($request->hasFile('foto')) {
            // Pindahkan file foto baru ke folder fotobarang
            $request->file('foto')->move('fotobarang/', $request->file('foto')->getClientOriginalName());
            // Simpan nama file foto baru ke dalam database
            $usage->foto = $request->file('foto')->getClientOriginalName();
            $usage->save();
        }

        //untuk mengatur notifikasi
        $successMessage = 'validated usage';
        if ($request->has('status_pengembalian') && $request->input('status_pengembalian') == 'Tervalidasi') {
            $successMessage = 'validated return';
        } elseif ($request->has('status_pengembalian') && $request->input('status_pengembalian') == 'Menunggu Validasi') {
            $successMessage = 'submitted the form';
        }

        return redirect()->route('usages.index')
            ->with('success', $successMessage);
    }

    public function truncate()
    {
        // Mengosongkan (truncate) tabel usages
        DB::table('usages')->truncate();

        return redirect()->route('usages.index')
            ->with('success', 'all history has been deleted');
    }


    // untuk menampilkan index validasi pemakaian
    public function index_validation()
    {
        // Ambil data penggunaan yang memiliki status_pemakaian 'Menunggu Validasi'
        $usages = Usage::where('status_pemakaian', 'Menunggu Validasi')->get();
        $placeholderFoto = 'nothing.png';
        return view('usages.index_validation', compact('usages','placeholderFoto'));
    }

    public function show_validation(Usage $usage)
    {
        // Tampilkan halaman detail untuk penggunaan tertentu
        return view('usages.show_validation', compact('usage'));
    }

    //validasi pengembalian
    public function edit_returnValidation(Usage $usage)
    {
        $inventorys = Inventory::all();
        $subjects = Subject::all();

        return view('usages.edit_returnValidation', compact('usage', 'inventorys', 'subjects'));
    }

    public function index_returnValidation()
    {
        $usages = Usage::where('status_pengembalian', 'Menunggu Validasi')->get();
        $placeholderFoto = 'nothing.png';
        return view('usages.index_returnValidation', compact('usages', 'placeholderFoto'));
    }    

    public function show_returnValidation(Usage $usage)
    {
        // Tampilkan halaman detail untuk penggunaan tertentu
        return view('usages.show_returnValidation', compact('usage'));
    }

    //Return Goods
    public function edit_returnGoods(Usage $usage)
    {
        $inventorys = Inventory::all();
        $subjects = Subject::all();

        return view('usages.edit_returnGoods', compact('usage', 'inventorys', 'subjects'));
    }

    // ----------------------------------------------------------------------------------------------------------------------//

    //Controller Untuk Dosen
    // BUAT HISTORY
    public function index_lecturer()
    {
        $usages = Usage::with('validator')->orderBy('created_at', 'desc')->get();
        $inventorys = Inventory::all();
        return view('usages.index_lecturer', compact('usages'));
    }

    //View Cetak
    public function cetak_laporan_lecturer()
    {
        $inventorys = Inventory::all();
        $usages = Usage::all();
        $usages = Usage::with('validator')->get();
        return view('reports.cetak_lecturer', compact('usages'));
    }

    //BUAT NAMPILIN TABEL BERDASRKAN OPSI JENIS BARANG     
    public function index_report_lecturer(Request $request)
    {
        // Get the selected option from the request
        $selectedOption = $request->input('jenis_barang_option');

        // Start building the query
        $query = Usage::query();

        // Join the inventory table to access the jenisbarang field
        $query->join('inventorys', 'usages.idbarang', '=', 'inventorys.idbarang');

        // Join the users table to access the idPengguna field
        $query->join('users', 'usages.idPengguna', '=', 'users.idPengguna');

        if ($selectedOption == 'Tools') {
            $query->where('inventorys.jenisbarang', 'tool');
            $query->where('status_pemakaian', 'Tervalidasi');
            $query->where('status_pengembalian', 'Tervalidasi');
        } elseif ($selectedOption == 'Materials') {
            $query->where('inventorys.jenisbarang', 'material');
            $query->where('status_pemakaian', 'Tervalidasi');
        } elseif ($selectedOption == 'All') {
            $query->where(function ($q) {
                $q->where('inventorys.jenisbarang', 'tool')
                    ->where('status_pemakaian', 'Tervalidasi')
                    ->where('status_pengembalian', 'Tervalidasi');
            })->orWhere(function ($q) {
                $q->where('inventorys.jenisbarang', 'material')
                    ->where('status_pemakaian', 'Tervalidasi');
            });
            $query->orderBy('usages.idpb', 'asc');
        }

        $usages = $query->get();

        return view('reports.index_lecturer', compact('usages'));
    }

    public function show_lecturer(Usage $usage)
    {
        // Tampilkan halaman detail untuk penggunaan tertentu
        return view('usages.show_lecturer', compact('usage'));
    }

    public function edit_lecturer(Usage $usage)
    {
        $inventorys = Inventory::all();
        $subjects = Subject::all();

        return view('usages.edit_lecturer', compact('usage', 'inventorys', 'subjects'));
    }

    public function update_lecturer(Request $request, Usage $usage)
    {
        // Validasi input dari form
        $request->validate([
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'nullable|date',
            'quantity_pinjam' => 'required|integer',
            'quantity_kembali' => 'nullable|integer',
            'kondisi_barang' => 'nullable|string|max:50',
            'status_pemakaian' => 'nullable|string|max:50',
            'status_pengembalian' => 'nullable|string|max:50',
            'validasi_pemakaian' => 'nullable|string|max:50',
            'validasi_pengembalian' => 'nullable|string|max:50',
            'brg_rusak' => 'nullable|integer',
            'foto' => 'nullable|file|image|mimes:jpeg,png,jpg,svg|max:3072',
            'idPengguna' => 'required|string|max:50',
            'idbarang' => 'required|integer',
            'idMatakuliah' => 'required|string|max:50',
        ]);

        // Perbarui data penggunaan di database
        $usage->update($request->all());

        // Periksa apakah ada file foto yang diunggah
        if ($request->hasFile('foto')) {
            // Pindahkan file foto baru ke folder fotobarang
            $request->file('foto')->move('fotobarang/', $request->file('foto')->getClientOriginalName());
            // Simpan nama file foto baru ke dalam database
            $usage->foto = $request->file('foto')->getClientOriginalName();
            $usage->save();
        }

        //untuk mengatur notifikasi
        $successMessage = 'validated usage';
        if ($request->has('status_pengembalian') && $request->input('status_pengembalian') == 'Tervalidasi') {
            $successMessage = 'validated return';
        } elseif ($request->has('status_pengembalian') && $request->input('status_pengembalian') == 'Menunggu Validasi') {
            $successMessage = 'submitted the form';
        }

        return redirect()->route('usages.index_lecturer')
            ->with('success', $successMessage);
    }

    public function truncate_lecturer()
    {
        // Mengosongkan (truncate) tabel usages
        DB::table('usages')->truncate();

        return redirect()->route('usages.index_lecturer')
            ->with('success', 'all history has been deleted');
    }

    // untuk menampilkan index validasi pemakaian
    public function index_validation_lecturer()
    {
        // Ambil data penggunaan yang memiliki status_pemakaian 'Menunggu Validasi'
        $usages = Usage::where('status_pemakaian', 'Menunggu Validasi')->get();
        $placeholderFoto = 'nothing.png';
        return view('usages.index_validation_lecturer', compact('usages','placeholderFoto'));
    }

    public function show_validation_lecturer(Usage $usage)
    {
        // Tampilkan halaman detail untuk penggunaan tertentu
        return view('usages.show_validation_lecturer', compact('usage'));
    }

    //validasi pengembalian
    public function edit_returnValidation_lecturer(Usage $usage)
    {
        $inventorys = Inventory::all();
        $subjects = Subject::all();

        return view('usages.edit_returnValidation_lecturer', compact('usage', 'inventorys', 'subjects'));
    }

    public function index_returnValidation_lecturer()
    {
        // Ambil data penggunaan yang memiliki status_pemakaian 'Menunggu Validasi'
        $usages = Usage::where('status_pengembalian', 'Menunggu Validasi')->get();
        $placeholderFoto = 'nothing.png';
        return view('usages.index_returnValidation_lecturer', compact('usages','placeholderFoto'));
    }

    public function show_returnValidation_lecturer(Usage $usage)
    {
        // Tampilkan halaman detail untuk penggunaan tertentu
        return view('usages.show_returnValidation_lecturer', compact('usage'));
    }

    //----------------------------------------------------------------------------------------------------------------------//
    //Controller Untuk Mahasiswa
    public function index_student()
    {
        // Ambil idPengguna dari pengguna yang sedang login
        $idPengguna = Auth::user()->idPengguna;

        // Ambil data penggunaan berdasarkan idPengguna dan urutkan descending berdasarkan created_at
        $usages = Usage::where('idPengguna', $idPengguna)
            ->orderBy('created_at', 'desc')
            ->get();

        // Kirim data ke view
        return view('usages.index_student', compact('usages'));
    }


    public function create_student()
    {
        $inventorys = Inventory::all();
        $subjects = Subject::all(); // Pastikan untuk mendapatkan data subjek juga
        return view('usages.create_student', compact('inventorys', 'subjects'));
    }


    public function store_student(Request $request)
    {
        // Validasi input dari form
        $request->validate([
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'nullable|date',
            'quantity_pinjam' => 'required|integer',
            'quantity_kembali' => 'nullable|integer',
            'kondisi_barang' => 'nullable|string|max:50',
            'status_pemakaian' => 'nullable|string|max:50',
            'status_pengembalian' => 'nullable|string|max:50',
            'validasi_pemakaian' => 'nullable|string|max:50',
            'validasi_pengembalian' => 'nullable|string|max:50',
            'brg_rusak' => 'nullable|integer',
            'foto' => 'nullable|string|image|mimes:jpeg,png,jpg,svg|max:3072',
            'idPengguna' => 'required|string|max:50',
            'idbarang' => 'required|integer',
            'idMatakuliah' => 'required|string|max:50',
        ]);

        // Simpan data penggunaan ke database
        Usage::create($request->all());

        return redirect()->route('usages.index_student')
            ->with('success', 'submitted the form');
    }

    public function show_student(Usage $usage)
    {
        // Tampilkan halaman detail untuk penggunaan tertentu
        return view('usages.show_student', compact('usage'));
    }

    public function edit_student(Usage $usage)
    {
        $inventorys = Inventory::all();
        $subjects = Subject::all();

        return view('usages.edit_student', compact('usage', 'inventorys', 'subjects'));
    }

    public function update_student(Request $request, Usage $usage)
    {
        // Validasi input dari form
        $request->validate([
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'nullable|date',
            'quantity_pinjam' => 'required|integer',
            'quantity_kembali' => 'nullable|integer',
            'kondisi_barang' => 'nullable|string|max:50',
            'status_pemakaian' => 'nullable|string|max:50',
            'status_pengembalian' => 'nullable|string|max:50',
            'validasi_pemakaian' => 'nullable|string|max:50',
            'validasi_pengembalian' => 'nullable|string|max:50',
            'brg_rusak' => 'nullable|integer',
            'foto' => 'nullable|file|image|mimes:jpeg,png,jpg,svg|max:3072',
            'idPengguna' => 'required|string|max:50',
            'idbarang' => 'required|integer',
            'idMatakuliah' => 'required|string|max:50',
        ]);

        // Perbarui data penggunaan di database
        $usage->update($request->all());

        // Periksa apakah ada file foto yang diunggah
        if ($request->hasFile('foto')) {
            // Pindahkan file foto baru ke folder fotobarang
            $request->file('foto')->move('fotobarang/', $request->file('foto')->getClientOriginalName());
            // Simpan nama file foto baru ke dalam database
            $usage->foto = $request->file('foto')->getClientOriginalName();
            $usage->save();
        }

        //untuk mengatur notifikasi
        $successMessage = 'validated usage';
        if ($request->has('status_pengembalian') && $request->input('status_pengembalian') == 'Tervalidasi') {
            $successMessage = 'validated return';
        } elseif ($request->has('status_pengembalian') && $request->input('status_pengembalian') == 'Menunggu Validasi') {
            $successMessage = 'submitted the form';
        }

        return redirect()->route('usages.index_student')
            ->with('success', $successMessage);
    }

    public function destroy_student(Usage $usage)
    {
        // Hapus data penggunaan dari database
        $usage->delete();

        return redirect()->route('usages.index_student')
            ->with('success', 'Penggunaan berhasil dihapus.');
    }

    public function edit_returnGoods_student(Usage $usage)
    {
        $inventorys = Inventory::all();
        $subjects = Subject::all();

        return view('usages.edit_returnGoods_student', compact('usage', 'inventorys', 'subjects'));
    }
}
