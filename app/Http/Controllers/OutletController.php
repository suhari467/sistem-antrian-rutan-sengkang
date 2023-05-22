<?php

namespace App\Http\Controllers;

use App\Models\Outlet;
use Illuminate\Http\Request;

class OutletController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data_outlet = Outlet::all();

        $data = [
            'title' => 'Data Outlet',
            'slug' => 'outlet',
            'data_outlet' => $data_outlet
        ];

        return view('outlet.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [
            'title' => 'Tambah Outlet',
            'slug' => 'outlet'
        ];

        return view('outlet.create', $data);
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
            'name' => 'required|max:32',
            'address' => 'required|max:32',
            'no_telp' => 'required|max:15',
            'running_text' => 'required'
        ]);

        $create = Outlet::create($validate);

        if($create){
            return redirect('/outlet')->with('success', 'Data outlet berhasil disimpan');
        }else{
            return redirect('/outlet')->with('error', 'Data outlet gagal disimpan');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Outlet  $outlet
     * @return \Illuminate\Http\Response
     */
    public function show(Outlet $outlet)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Outlet  $outlet
     * @return \Illuminate\Http\Response
     */
    public function edit(Outlet $outlet)
    {
        $data = [
            'title' => 'Edit Outlet',
            'slug' => 'outlet',
            'outlet' => $outlet
        ];

        return view('outlet.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Outlet  $outlet
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Outlet $outlet)
    {
        $validate = $request->validate([
            'name' => 'required|max:32',
            'address' => 'required|max:255',
            'no_telp' => 'required|max:15',
            'running_text' => 'required'
        ]);

        $update = Outlet::where('id', $outlet->id)->update($validate);

        if($update){
            return redirect('/outlet')->with('success', 'Data outlet berhasil diubah');
        }else{
            return redirect('/outlet')->with('error', 'Data outlet gagal diubah. Harap coba lagi');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Outlet  $outlet
     * @return \Illuminate\Http\Response
     */
    public function destroy(Outlet $outlet)
    {
        $status = $outlet->status;
        if($status == 'aktif'){
            return redirect('/outlet')->with('error', 'Data outlet tidak dapat dihapus!, karena masih aktif.');
        }
        
        $destroy = Outlet::destroy($outlet->id);

        if($destroy){
            return redirect('/outlet')->with('success', 'Data outlet telah berhasil dihapus');
        }else{
            return redirect('/outlet')->with('error', 'Data outlet tidak dapat dihapus');
        }
    }

    public function aktifOutlet(Outlet $outlet)
    {
        $data['status'] = 'aktif';

        $outlet_sebelumnya = Outlet::where('status', $data['status'])->first();
        if(!$outlet_sebelumnya){
            $aktifOutlet = Outlet::where('id', $outlet->id)->update($data);
        }else{
            $change = Outlet::where('id', $outlet_sebelumnya->id)->update(['status'=>null]);
            if(!$change){
                return redirect('/outlet')->with('error', 'Data outlet sebelumnya tidak dapat dikembalikan');
            }
            $aktifOutlet = Outlet::where('id', $outlet->id)->update($data);
        }

        if($aktifOutlet){
            return redirect('/outlet')->with('success', 'Data status outlet telah berhasil diubah');
        }else{
            return redirect('/outlet')->with('error', 'Data status outlet tidak dapat diubah');
        }
    }
}
