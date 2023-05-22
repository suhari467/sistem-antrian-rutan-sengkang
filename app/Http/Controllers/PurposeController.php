<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Purpose;
use App\Models\Queue;

class PurposeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $purposes = Purpose::all();
        $data = [
            'title' => 'Data Tujuan Loket',
            'slug' => 'purpose',
            'purposes' => $purposes
        ];

        return view('purpose.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $purposes = Purpose::all();

        $kode_terpakai = DB::table('purposes')
                            ->select('kode')
                            ->get();
        $kode_terpakai = json_encode($kode_terpakai);

        $kode_antrian = range('A', 'Z');

        $data = [
            'title' => 'Tambah Tujuan Loket',
            'slug' => 'purpose',
            'purposes' => $purposes,
            'kode_antrian' => $kode_antrian,
            'kode_terpakai' => $kode_terpakai
        ];

        // ddd($data);

        return view('purpose.create', $data);
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
            'kode' => 'required|unique:purposes',
            'jenis' => 'required|alpha_num|max:255|unique:purposes',
            'keterangan' => 'required|max:32'
        ]);

        // ddd($validate);
        $create = Purpose::create($validate);

        if($create){
            return redirect('/setting/purpose')->with('success', 'Data tujuan loket berhasil dibuat');
        }else{
            return redirect('/setting/purpose')->with('error', 'Data tujuan loket gagal dibuat');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Purpose $purpose)
    {
        $data = [
            'title' => 'Data Loket pada Tujuan Loket',
            'slug' => 'purpose',
            'data_loket' => $purpose->loket
        ];

        // ddd($data);

        return view('purpose.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Purpose $purpose)
    {
        $kode_antrian = range('A', 'Z');

        $data = [
            'title' => 'Edit Tujuan Loket',
            'slug' => 'purpose',
            'purpose' => $purpose,
            'kode_antrian' => $kode_antrian
        ];

        // ddd($data);

        return view('purpose.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Purpose $purpose)
    {
        $rules = [
            'keterangan' => 'required|max:32'
        ];

        if($request->kode != $purpose->kode){
            $rules['kode'] = 'required|unique:purposes';
        }

        if($request->jenis != $purpose->jenis){
            $rules['kode'] = 'required|alpha_num|max:255|unique:purposes';
        }

        $validate = $request->validate($rules);

        // ddd($validate);
        $update = Purpose::where('id', $purpose->id)->update($validate);

        if($update){
            return redirect('/setting/purpose')->with('success', 'Data tujuan loket berhasil diubah');
        }else{
            return redirect('/setting/purpose')->with('error', 'Data tujuan loket gagal diubah');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Purpose $purpose)
    {
        $loket = $purpose->loket->count();
        // ddd($loket);
        if($loket > 0){
            return redirect('/setting/purpose')->with('error', 'Data tujuan loket tidak dapat dihapus!, karena masih ada loket tertaut.');
        }
        
        $antrian = Queue::where('purpose_id', $purpose->id)->count();
        // ddd($antrian);
        if($antrian > 0){
            return redirect('/setting/purpose')->with('error', 'Data tujuan loket tidak dapat dihapus!, karena masih ada nomer antrian aktif tertaut.');
        }
        
        $destroy = Purpose::destroy($purpose->id);

        if($destroy){
            return redirect('/setting/purpose')->with('success', 'Data tujuan loket telah berhasil dihapus');
        }else{
            return redirect('/setting/purpose')->with('error', 'Data tujuan loket tidak dapat dihapus');
        }
    }
}
