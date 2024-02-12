<?php
// app/Http/Controllers/InventoryController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\User;
use App\Models\Usage;
use Illuminate\Support\Facades\File;

// Controler untuk Lecturers
class InventoryController extends Controller
{
    public function index()
    {
        $inventoryItems = Inventory::all();
        return view('inventorys.index', compact('inventoryItems'));
    }

    public function create()
    {
        $users = User::all();
        return view('inventorys.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'idtype' => 'required|string|max:50',
            'namabarang' => 'required|string|max:50',
            'jenisbarang' => 'required|string|max:50',
            'jumlahbarang' => 'required|integer',
            'satuan' => 'required|string|max:50',
            'uom' => 'nullable|string|max:50',
            'foto' => 'nullable|file|image|mimes:jpeg,png,jpg,svg|max:3072',
            'modifiedbydate' => 'required|date',
            'idPengguna' => 'required|string|max:50',
        ]);

        // Pastikan bahwa nilai jumlahbarang tidak kurang dari 0
        $jumlahbarang = max(0, $request->jumlahbarang);

        $data = Inventory::create($request->all());

        if ($request->hasFile('foto')) {
            $request->file('foto')->move('fotobarang/', $request->file('foto')->getClientOriginalName());
            $data->foto = $request->file('foto')->getClientOriginalName();
            $data->save();
        }

        return redirect()->route('inventorys.index')->with('success', 'adding item data');
    }

    public function show($idbarang)
    {
        $inventoryItem = Inventory::with('user')->where('idbarang', $idbarang)->first();
        return view('inventorys.show', compact('inventoryItem'));
    }

    public function edit($idbarang)
    {
        $inventoryItem = Inventory::find($idbarang);
        $users = User::all();
        return view('inventorys.edit', compact('inventoryItem', 'users'));
    }

    public function update(Request $request, $idbarang)
    {
        $inventoryItem = Inventory::find($idbarang);

        // Setiap atribut diatur satu per satu
        $inventoryItem->namabarang = $request->input('namabarang');
        $inventoryItem->jenisbarang = $request->input('jenisbarang');
        $inventoryItem->jumlahbarang = $request->input('jumlahbarang');
        $inventoryItem->satuan = $request->input('satuan');
        $inventoryItem->uom = $request->input('uom');
        $inventoryItem->modifiedbydate = $request->input('modifiedbydate');
        $inventoryItem->idPengguna = $request->input('idPengguna');

        // Hapus foto lama jika ada
        if ($inventoryItem->foto) {
            $fotoPath = public_path('fotobarang/' . $inventoryItem->foto);
            if (File::exists($fotoPath)) {
                // Hapus foto lama
                File::delete($fotoPath);
            }
        }
        // Periksa apakah ada file foto yang diunggah
        if ($request->hasFile('foto')) {
            // Pindahkan file foto baru ke folder fotobarang
            $request->file('foto')->move('fotobarang/', $request->file('foto')->getClientOriginalName());
            // Simpan nama file foto baru ke dalam database
            $inventoryItem->foto = $request->file('foto')->getClientOriginalName();
        }
        $inventoryItem->save();

        return redirect()->route('inventorys.index')->with('success', 'update item data');
    }

    public function destroy($idbarang)
    {
        // Periksa apakah ada entri di tabel usages yang menggunakan idbarang
        $usagesCount = Usage::where('idbarang', $idbarang)->count();
        if ($usagesCount > 0) {
            // Ada entri di tabel usages yang menggunakan idbarang, lakukan tindakan yang sesuai
            return redirect()->route('inventorys.index')->with('error', 'Item cannot be deleted, it is used in usages.');
        }
        // Tidak ada entri di tabel usages yang menggunakan idbarang, lanjutkan menghapus
        $inventoryItem = Inventory::find($idbarang);
        if (!$inventoryItem) {
            return redirect()->route('inventorys.index')->with('error', 'Item not found.');
        }
        // Hapus foto dari folder fotobarang
        if ($inventoryItem->foto) {
            $fotoPath = public_path('fotobarang/' . $inventoryItem->foto);
            if (File::exists($fotoPath)) {
                File::delete($fotoPath);
            }
        }
        // Hapus data dari database
        $inventoryItem->delete();

        return redirect()->route('inventorys.index')->with('success', 'Item data deleted successfully.');
    }


    // Controller untuk Admin
    public function index_admin()
    {
        $inventoryItems = Inventory::all();
        return view('inventorys.index_admin', compact('inventoryItems'));
    }

    public function create_admin()
    {
        $users = User::all();
        return view('inventorys.create_admin', compact('users'));
    }

    public function store_admin(Request $request)
    {
        $request->validate([
            'idtype' => 'required|string|max:50',
            'namabarang' => 'required|string|max:50',
            'jenisbarang' => 'required|string|max:50',
            'jumlahbarang' => 'required|integer',
            'satuan' => 'required|string|max:50',
            'uom' => 'nullable|string|max:50',
            'foto' => 'nullable|file|image|mimes:jpeg,png,jpg,svg|max:3072',
            'modifiedbydate' => 'required|date',
            'idPengguna' => 'required|string|max:50',
        ]);

        // Pastikan bahwa nilai jumlahbarang tidak kurang dari 0
        $jumlahbarang = max(0, $request->jumlahbarang);

        $data = Inventory::create($request->all());

        if ($request->hasFile('foto')) {
            $request->file('foto')->move('fotobarang/', $request->file('foto')->getClientOriginalName());
            $data->foto = $request->file('foto')->getClientOriginalName();
            $data->save();
        }

        return redirect()->route('inventorys.index_admin')->with('success', 'adding item data');
    }


    public function show_admin($idbarang)
    {
        $inventoryItem = Inventory::with('user')->where('idbarang', $idbarang)->first();
        return view('inventorys.show_admin', compact('inventoryItem'));
    }

    public function edit_admin($idbarang)
    {
        $inventoryItem = Inventory::find($idbarang);
        $users = User::all();
        return view('inventorys.edit_admin', compact('inventoryItem', 'users'));
    }

    public function update_admin(Request $request, $idbarang)
    {
        $inventoryItem = Inventory::find($idbarang);

        // Setiap atribut diatur satu per satu
        $inventoryItem->namabarang = $request->input('namabarang');
        $inventoryItem->jenisbarang = $request->input('jenisbarang');
        $inventoryItem->jumlahbarang = $request->input('jumlahbarang');
        $inventoryItem->satuan = $request->input('satuan');
        $inventoryItem->uom = $request->input('uom');
        $inventoryItem->modifiedbydate = $request->input('modifiedbydate');
        $inventoryItem->idPengguna = $request->input('idPengguna');

        // Hapus foto lama jika ada
        if ($inventoryItem->foto) {
            $fotoPath = public_path('fotobarang/' . $inventoryItem->foto);
            if (File::exists($fotoPath)) {
                // Hapus foto lama
                File::delete($fotoPath);
            }
        }
        // Periksa apakah ada file foto yang diunggah
        if ($request->hasFile('foto')) {
            // Pindahkan file foto baru ke folder fotobarang
            $request->file('foto')->move('fotobarang/', $request->file('foto')->getClientOriginalName());
            // Simpan nama file foto baru ke dalam database
            $inventoryItem->foto = $request->file('foto')->getClientOriginalName();
        }
        $inventoryItem->save();

        return redirect()->route('inventorys.index_admin')->with('success', 'update item data');
    }


    public function destroy_admin($idbarang)
    {
        // Periksa apakah ada entri di tabel usages yang menggunakan idbarang
        $usagesCount = Usage::where('idbarang', $idbarang)->count();
        if ($usagesCount > 0) {
            // Ada entri di tabel usages yang menggunakan idbarang, lakukan tindakan yang sesuai
            return redirect()->route('inventorys.index_admin')->with('error', 'You have to clear all history to delete item');
        }
        // Tidak ada entri di tabel usages yang menggunakan idbarang, lanjutkan menghapus
        $inventoryItem = Inventory::find($idbarang);
        if (!$inventoryItem) {
            return redirect()->route('inventorys.index_admin')->with('error', 'Item not found.');
        }
        // Hapus foto dari folder fotobarang
        if ($inventoryItem->foto) {
            $fotoPath = public_path('fotobarang/' . $inventoryItem->foto);
            if (File::exists($fotoPath)) {
                File::delete($fotoPath);
            }
        }
        // Hapus data dari database
        $inventoryItem->delete();

        return redirect()->route('inventorys.index_admin')->with('success', 'Item data deleted successfully.');
    }
}
