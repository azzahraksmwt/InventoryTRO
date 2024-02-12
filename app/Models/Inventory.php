<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $table = 'inventorys'; // Sesuaikan dengan nama tabel yang sebenarnya
    protected $primaryKey = 'idbarang'; // Sesuaikan dengan nama primary key yang sebenarnya
    protected $keyType = 'integer';
    public $incrementing = true;
    protected $fillable = [
        'idbarang',
        'idtype',
        'namabarang',
        'jenisbarang',
        'jumlahbarang',
        'satuan',
        'uom',
        'foto',
        'modifiedbydate',
        'idPengguna',
    ];

    
    // Tambahan jika ingin menetapkan relasi dengan model Lecturer
    public function user1()
    {
        return $this->belongsTo(User::class, 'idPengguna', 'idPengguna');
    }
}
