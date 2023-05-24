<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PrinterController extends Controller
{
    //
    public function index()
    {
        $fetch = json_decode(Storage::disk('public')->get('printer.json'));
        $printer = collect($fetch->data)->sortByDesc('status')->all();

        $data = [
            'title' => 'Data Printer Antrian',
            'slug' => 'printer',
            'printer' => $printer
        ];

        // ddd($data);

        return view('printer.index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Printer Antrian',
            'slug' => 'printer'
        ];

        return view('printer.create', $data);
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'printer' => 'required|max:255',
            'appKey' => 'required|numeric'
        ]);

        // $file = file_get_contents('storage/printer.json');

        $get = json_decode(Storage::disk('public')->get('printer.json'), true);
        $get['data'] = array_values($get['data']);

        $no = 1;
        $data = collect($get['data']);

        if ($data->count()){
            $last = $data->last();
            $no = ((int)$last['id'])+1;
        }
        
        $validate['id'] = $no;
        $validate['status'] = 0;
        
        array_push($get['data'], $validate);

        $store = Storage::disk('public')->put('printer.json', json_encode($get));

        if($store){
            return redirect('setting/printer')->with('success', 'Data berhasil di tambah');
        }else{
            return redirect('setting/printer')->with('error', 'Data gagal untuk di tambah');
        }
    }

    public function edit(Request $request)
    {
        $id = $request->printer;

        // $file = file_get_contents('storage/printer.json');
        $process = json_decode(Storage::disk('public')->get('printer.json'), true);
        $data = collect($process['data']);

        $printer = $data->where('id', $id)->first(); //cari data yang mau hapus

        $data = [
            'title' => 'Edit Printer Antrian',
            'slug' => 'printer',
            'printer' => $printer
        ];
        // ddd($data);

        return view('printer.edit', $data);
    }

    public function update(Request $request)
    {
        $validate = $request->validate([
            'id' => 'required',
            'printer' => 'required|max:255',
            'appKey' => 'required|numeric'
        ]);

        // ddd($validate);

        // $file = file_get_contents('storage/printer.json');
        $get = json_decode(Storage::disk('public')->get('printer.json'), true);

        $data = collect($get['data']);
        $data_old = $data->where('id', $validate['id'])->first(); //cari data yang mau diubah statusnya

        //ubah masing masing data statusnya
        $data_old['id'] = $validate['id']; 
        $data_old['printer'] = $validate['printer']; 
        $data_old['appKey'] = $validate['appKey']; 

        //mencari index dengan id
        foreach($data as $index => $item){
            $nomor[] = [
                'id' => $item['id'],
                'index' => $index
            ];
        }

        $fetch = collect($nomor); //membangun data untuk mencari index
        $search_old = $fetch->where('id', $data_old['id'])->first();
        $remove_old = $data->forget($search_old['index']); //remove data by index

        $data = $data->all();
        array_push($data, $data_old);

        $array = ['data' => $data];
        // ddd($array);

        $update = Storage::disk('public')->put('printer.json', json_encode($array));

        if($update){
            return redirect('setting/printer')->with('success', 'Data setting berhasil di ubah');
        }else{
            return redirect('setting/printer')->with('error', 'Data setting gagal untuk di ubah');
        }
    }

    public function status(Request $request)
    {
        $id = $request->printer;

        // $file = file_get_contents('storage/printer.json');
        $get = json_decode(Storage::disk('public')->get('printer.json'), true);

        $data = collect($get['data']);
        $data_new = $data->where('id', $id)->first(); //cari data yang mau diubah statusnya
        $data_old = $data->where('status', 1)->first(); //cari data status lama
        
        if($data_new['status']==1){ //jika yang mau diubah ternyata statusnya udah aktif, jadi error
            return redirect('setting/printer')->with('error', 'Data sudah aktif');
        }

        //ubah masing masing data statusnya
        $data_new['status'] = 1; //1 berarti aktif
        $data_old['status'] = 0; 

        //mencari index dengan id
        foreach($data as $index => $item){
            $nomor[] = [
                'id' => $item['id'],
                'index' => $index
            ];
        }

        $fetch = collect($nomor); //membangun data untuk mencari index
        $search_new = $fetch->where('id', $data_new['id'])->first();
        $search_old = $fetch->where('id', $data_old['id'])->first();

        $remove_new = $data->forget($search_new['index']); //remove data by index
        $remove_old = $data->forget($search_old['index']); //remove data by index


        // unset($get['data'][$id]);
        // $get['data'][$id] = $data_id;
         
        // $remove = $data->intersectByKeys(['id' => $id]); //remove just id
        // $remove = $data->pull('id');
        // $remove = $data->whereNotIn ('id', [$id]);
        // $index = array_keys($get['data'], $id);

        $data = $data->all();
        // ddd($data);

        array_push($data, $data_old);
        array_push($data, $data_new);


        $array = ['data' => $data];
        // ddd($array);

        $status = Storage::disk('public')->put('printer.json', json_encode($array));

        if($status){
            return redirect('setting/printer')->with('success', 'Data status berhasil di ubah');
        }else{
            return redirect('setting/printer')->with('error', 'Data status gagal untuk di ubah');
        }
    }

    public function destroy(Request $request)
    {
        $id = $request->printer;

        // $file = file_get_contents('storage/printer.json');
        $process = json_decode(Storage::disk('public')->get('printer.json'), true);
        $data = collect($process['data']);

        $fetch = $data->where('id', $id)->first(); //cari data yang mau hapus

        if($fetch['status']==1){ //jika yang mau diubah ternyata statusnya udah aktif, jadi error
            return redirect('setting/printer')->with('error', 'Data aktif tidak dapat dihapus');
        }

        foreach($data as $index => $item){
            $nomor[] = [
                'id' => $item['id'],
                'index' => $index
            ];
        }

        $get = collect($nomor); //membangun data untuk mencari index
        $search = $get->where('id', $fetch['id'])->first();
        $remove = $data->forget($search['index']); //remove data by index

        // ddd($data);
        $array = ['data' => $data];

        $destroy = Storage::disk('public')->put('printer.json', json_encode($array));
        if($destroy){
            return redirect('setting/printer')->with('success', 'Data berhasil di hapus');
        }else{
            return redirect('setting/printer')->with('error', 'Data gagal untuk di hapus');
        }
    }
}
