<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Queue extends Model
{
    use HasFactory;

    protected $guarded = [ 'id' ];
    protected $with = ['purpose'];

    public static function antrian($kode_antrian)
    {
        $query = DB::table('queues')
                    ->select(DB::raw('MAX(SUBSTR(nomor_antrian,2)) as nomor_antrian_terakhir'))
                    ->where('nomor_antrian', 'like', $kode_antrian.'%');
        $no = 1;

        if ($query->count()){
            $last = $query->first();
            $no = ((int)$last->nomor_antrian_terakhir)+1;
        }

        $formatedNo = sprintf("%03s", $no);
        $no_antrian = $kode_antrian.$formatedNo;

        return $no_antrian;
    }

    public function purpose()
    {
        return $this->belongsTo(Purpose::class);
    }
}
