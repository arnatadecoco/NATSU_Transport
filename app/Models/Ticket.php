<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use SoftDeletes;

    // Guarded = [] artinya semua kolom BOLEH diisi (termasuk seat_number & status)
    // Jadi ini sudah aman, ga perlu ganti ke $fillable
    protected $guarded = []; 

    protected $with = ['flight', "user"];

    // --- BAGIAN INI GUA HAPUS BIAR CONTROLLER BISA BACA ANGKA 0, 1, 2 ---
    /*
    public function getStatusAttribute($value)
    {
        switch ($value) {
            case 0: return "pending"; break;
            case 1: return "approved"; break;
            case 2: return "cancelled"; break;
            default: return "not defined"; break;
        }
    }
    */
    // --------------------------------------------------------------------

    public function flight()
    {
        return $this->belongsTo(Flight::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}