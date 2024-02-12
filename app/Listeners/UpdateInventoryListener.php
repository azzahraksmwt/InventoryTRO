<?php

namespace App\Listeners;

use App\Events\UpdateInventory;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateInventoryListener
{

    // UpdateInventoryListener.php

    public function handle(UpdateInventory $event)
    {
        $usage = $event->usage;
        $inventory = $usage->inventory;
    
        // Log values before modification
        logger("Before modification - jumlahbarang: {$inventory->jumlahbarang}, quantity_pinjam: {$usage->quantity_pinjam}");
    
        // Check if status_pemakaian is 'Ditolak' or 'Tervalidasi' before modification
        if ($usage->status_pemakaian == 'Ditolak') {
            $inventory->jumlahbarang += $usage->quantity_pinjam;
        } elseif ($usage->status_pengembalian == 'Tervalidasi') {
            $inventory->jumlahbarang += $usage->quantity_pinjam - $usage->brg_rusak;
        } elseif ($usage->status_pemakaian == 'Tervalidasi') {
             // Subtract quantity if validated
        } else {
            $inventory->jumlahbarang -= $usage->quantity_pinjam; 
        }
    
        // Log values after modification
        logger("After modification - jumlahbarang: {$inventory->jumlahbarang}, quantity_pinjam: {$usage->quantity_pinjam}");
    
        // Save the changes to inventory
        $inventory->save();
    }
    
    

}
