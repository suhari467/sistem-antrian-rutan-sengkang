<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\Queue;
use App\Models\Outlet;
use App\Models\Display;
use App\Models\Purpose;

class QueueController extends Controller
{
    public function index()
    {
        $file = Storage::disk('public')->get('printer.json');
        $file = json_decode($file);
        $file = collect($file->data);

        $printer = $file->where('status', 1)->first();

        $data = [
            'title' => 'Ambil Antrian',
            'appKey' => $printer->appKey,
            'purpose' => Purpose::all(),
            'outlet' => Outlet::where('status', 'aktif')->first()
        ];

        return view('global.antrian', $data);
    }

    public function display()
    {
        $data = [
            'title' => 'Display Antrian',
            'outlet' => Outlet::where('status', 'aktif')->first(),
            'display' => Display::where('status', 'aktif')->first()
        ];
        return view('global.display', $data);
    }

    public function antrian(Request $request)
    {
        $id_antrian = $request->id_antrian;

        $jenis_antrian = Purpose::where('id', $id_antrian)->first();
        $kode_antrian = $jenis_antrian->kode;

        $nomor_antrian = Queue::antrian($kode_antrian);

        $data = [
            'nomor_antrian' => $nomor_antrian,
            'purpose_id' => $id_antrian
        ];

        Queue::create($data);
        
        $antrian = Queue::where('nomor_antrian', $nomor_antrian)->first();
        $outlet = Outlet::where('status', 'aktif')->first();
        $count = Queue::where('nomor_loket', null)
                        ->where('purpose_id', $id_antrian)
                        ->count();

        $data['jenis_antrian'] = $jenis_antrian->jenis;
        $data['keterangan'] = $jenis_antrian->keterangan;
        $data['count'] = $count;
        $data['nama_outlet'] = $outlet->name;
        $data['alamat_outlet'] = $outlet->address;
        $data['no_telp'] = $outlet->no_telp;
        $data['tanggal'] = date('d-m-Y H:i:s', strtotime($antrian->created_at));
        $data['hari'] = date('l', strtotime($antrian->created_at));
        switch ($data['hari']) {
            case 'Monday':
                $data['hari'] = 'Senin';
                break;
            case 'Tuesday':
                $data['hari'] = 'Selasa';
                break;
            case 'Wednesday':
                $data['hari'] = 'Rabu';
                break;
            case 'Thursday':
                $data['hari'] = 'Kamis';
                break;
            case 'Friday':
                $data['hari'] = "jum'at";
                break;
            case 'Saturday':
                $data['hari'] = 'Sabtu';
                break;
            case 'Sunday':
                $data['hari'] = 'Minggu';
                break;
            default:
                $data['hari'] = '';
                break;
        }
        
        return json_encode($data);
    }
}
