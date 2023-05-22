<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\Role;
use App\Models\Loket;
use App\Models\Purpose;
use App\Models\Queue;
use App\Models\Outlet;
use App\Models\User;

class LoketController extends Controller
{
    public function rincian()
    {
        $antrian = Queue::where('nomor_loket', null)->orderBy('nomor_antrian')->get();
        $antrianRealtime = Queue::where('nomor_loket', '!=', null)->orderBy('nomor_antrian')->get();

        $id = Auth::id();
        $user = User::where('id', $id)->first();
        $outlet = Outlet::where('status', 'aktif')->first();

        $data = [
            'title' => 'Rincian Loket',
            'data_antrian' => $antrian,
            'data_antrian_realtime' => $antrianRealtime,
            'user' => $user,
            'outlet' => $outlet
        ];

        // ddd($data);

        return view('loket.rincian', $data);
    }

    public function proses(Request $request)
    {
        $user_id = $request->user_id;
        $user = User::find($user_id);
        $nomor_loket = $user->loket->nomor;
        $purpose_id = $user->loket->purpose->id;
        $jenis_loket = $user->loket->purpose->jenis;

        $antrian_panggil = Queue::where('nomor_loket', null)
                            ->where('purpose_id', $purpose_id)
                            ->orderBy('nomor_antrian')
                            ->first();
        $antrian_sebelumnya = Queue::where('nomor_loket', $nomor_loket)
                            ->where('purpose_id', $purpose_id)
                            ->first();
        // $antrian_panggil = $antrian->first();

        $data = [
            'user_id' => $user_id,
            'user' => $user,
            'nomor_loket' => $nomor_loket,
            'jenis_loket' => $jenis_loket,
            'antrian_sebelumnya' => $antrian_sebelumnya,
            'antrian_panggil' => $antrian_panggil
            
        ];

        // ddd($data);

        if($antrian_sebelumnya && $antrian_panggil){
            Queue::where('id', $antrian_sebelumnya->id)->delete();
        }

        if($antrian_panggil){
            Queue::where('id', $antrian_panggil->id)->update(['nomor_loket' => $nomor_loket]);
        }

        return json_encode($data);
    }

    public function tabelAntrianAktif()
    {
        // $data['data'] = Queue::where('nomor_loket', '!=', null)->orderBy('nomor_antrian')->get();
        $data['data'] = DB::table('queues')
                        ->join('purposes', 'queues.purpose_id', '=', 'purposes.id')
                        ->select('queues.*', 'purposes.*')
                        ->where('queues.nomor_loket', '!=', null)
                        ->orderBy('queues.nomor_antrian')
                        ->get();

        return json_encode($data);
    }

    public function fotoUserAktif(Request $request)
    {
        $nomor_loket = $request->loket;
        // $data['data'] = Queue::where('nomor_loket', '!=', null)->orderBy('nomor_antrian')->get();
        $data['data'] = Loket::where('nomor', $nomor_loket)->first();

        return json_encode($data);
    }

    public function ambilDetailAntrian(Request $request)
    {
        $user_id = $request->user_id;
        $user = User::find($user_id);
        $nomor_loket = $user->loket->nomor;
        $antrian_dipanggil = Queue::where('nomor_loket', $nomor_loket)->first();
        $nomor_antrian = "";
        if($antrian_dipanggil){
            $nomor_antrian = $antrian_dipanggil->nomor_antrian;
        }

        $data = [];
        if($nomor_antrian){
            $data = [
                'nomor_loket' => $nomor_loket,
                'nomor_antrian' => $nomor_antrian
            ];
        }

        return json_encode($data);
    }

    public function index()
    {
        $data_loket = Loket::all();
        $data = [
            'title' => 'Data Loket',
            'slug' => 'loket',
            'data_loket' => $data_loket
        ];

        return view('loket.index', $data);
    }

    public function create()
    {
        $purposes = Purpose::all();
        $data = [
            'title' => 'Add Loket',
            'slug' => 'loket',
            'purposes' => $purposes
        ];

        return view('loket.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required|max:255',
            'nomor' => 'required|numeric|max:255|unique:lokets',
            'purpose_id' => 'required|numeric|max:255',
        ]);

        // ddd($validate);
        $create = Loket::create($validate);

        if($create){
            return redirect('/setting/loket')->with('success', 'Data loket berhasil disimpan');
        }else{
            return redirect('/setting/loket')->with('error', 'Data loket gagal disimpan');
        }
    }

    public function show(Loket $loket)
    {
        $users = $loket->users;

        $data = [
            'title' => 'Data Pengguna pada Loket',
            'slug' => 'loket',
            'users' => $users
        ];

        return view('loket.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Loket $loket)
    {
        $purposes = Purpose::all();
        $data = [
            'title' => 'Edit Loket',
            'slug' => 'loket',
            'purposes' => $purposes,
            'loket' => $loket
        ];

        // dd($data);

        return view('loket.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Loket $loket)
    {
        $validate = $request->validate([
            'name' => 'required|max:255',
            'nomor' => 'required|numeric|max:255',
            'purpose_id' => 'required|numeric|max:255',
        ]);

        // ddd($validate);
        $update = Loket::where('id', $loket->id)->update($validate);

        if($update){
            return redirect('/setting/loket')->with('success', 'Data loket berhasil diubah');
        }else{
            return redirect('/setting/loket')->with('error', 'Data loket gagal diubah. Harap coba lagi');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Loket $loket)
    {
        // ddd($loket);
        $users = $loket->users->count();
        if($users > 0){
            return redirect('/setting/loket')->with('error', 'Data loket tidak dapat dihapus!, karena masih ada pengguna tertaut.');
        }
        
        $destroy = Loket::destroy($loket->id);

        if($destroy){
            return redirect('/setting/loket')->with('success', 'Data loket telah berhasil dihapus');
        }else{
            return redirect('/setting/loket')->with('error', 'Data loket tidak dapat dihapus');
        }
    }
}
