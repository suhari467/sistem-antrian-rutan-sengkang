<?php

namespace App\Http\Controllers;

use App\Models\Display;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingDisplayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $displays = Display::all();

        $data = [
            'title' => 'Data Antarmuka',
            'slug' => 'display',
            'displays' => $displays
        ];

        return view('display.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [
            'title' => 'Tambah Antarmuka',
            'slug' => 'display'
        ];

        return view('display.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data['keterangan'] = $request->keterangan;

        if($data['keterangan']=='youtube'){
            $validate = $request->validate([
                'name' => 'required|max:255',
                'source_youtube' => 'required|url'
            ]);
            $data['source'] = $validate['source_youtube'];

            $remove_http = str_replace('https://', '', $data['source']);
            $split_url = explode('?', $remove_http);
            $get_website = explode('/', $split_url[0]);
            $get_parameter = explode('=', $split_url[1]);
            $videoSource = $get_website[0];
            $videoID = $get_parameter[1];

            $data['name'] = $validate['name'];
            $data['source'] = $videoID;
        }

        if($data['keterangan']=='local'){
            $validate = $request->validate([
                'name' => 'required|max:255',
                'source_local' => 'required|file|max:102400'
            ]);

            date_default_timezone_set('Asia/Jakarta');
            $namaBerkas = date('d_m_Y_H_i_s');
            $extension = $request->file('source_local')->getClientOriginalExtension();
            $filenameSimpan = $namaBerkas.'.'.$extension;
            $upload = $request->file('source_local')->move('storage/display', $filenameSimpan);

            $data['name'] = $validate['name'];
            $data['source'] = $upload->getFilename();
        }
        
        $create = Display::create($data);

        if($create){
            return redirect('/setting/display')->with('success', 'Data setting antarmuka telah berhasil dibuat');
        }else{
            return redirect('/setting/display')->with('error', 'Data setting antarmuka tidak dapat dibuat');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Display  $display
     * @return \Illuminate\Http\Response
     */
    public function show(Display $display)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Display  $display
     * @return \Illuminate\Http\Response
     */
    public function edit(Display $display)
    {
        $data = [
            'title' => 'Data Antarmuka',
            'slug' => 'display',
            'display' => $display
        ];

        return view('display.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Display  $display
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Display $display)
    {
        $data['keterangan'] = $request->keterangan;

        if($data['keterangan']=='youtube'){
            $validate = $request->validate([
                'name' => 'required|max:255',
                'source_youtube' => 'required|url'
            ]);
            $data['source'] = $validate['source_youtube'];

            $keterangan = $display->keterangan;
            if($keterangan=='local'){
                $deleteFile = Storage::delete('display/'.$display->source);
                if(!$deleteFile){
                    return redirect('/setting/display')->with('error', 'Data file setting antarmuka tidak dapat dihapus');
                }
            }

            $remove_http = str_replace('https://', '', $data['source']);
            $split_url = explode('?', $remove_http);
            $get_website = explode('/', $split_url[0]);
            $get_parameter = explode('=', $split_url[1]);
            $videoSource = $get_website[0];
            $videoID = $get_parameter[1];

            $data['name'] = $validate['name'];
            $data['source'] = $videoID;
        }

        if($data['keterangan']=='local'){
            $validate = $request->validate([
                'name' => 'required|max:255',
                'source_local' => 'required|file|max:102400'
            ]);

            $keterangan = $display->keterangan;
            if($keterangan=='local'){
                $deleteFile = Storage::delete('display/'.$display->source);
                if(!$deleteFile){
                    return redirect('/setting/display')->with('error', 'Data file setting antarmuka tidak dapat dihapus');
                }
            }
            
            date_default_timezone_set('Asia/Jakarta');
            $namaBerkas = date('d_m_Y_H_i_s');
            $extension = $request->file('source_local')->getClientOriginalExtension();
            $filenameSimpan = $namaBerkas.'.'.$extension;
            $upload = $request->file('source_local')->move('storage/display', $filenameSimpan);

            $data['name'] = $validate['name'];
            $data['source'] = $upload->getFilename();
        }
        
        $update = Display::where('id', $display->id)->update($data);

        if($update){
            return redirect('/setting/display')->with('success', 'Data setting antarmuka telah berhasil diubah');
        }else{
            return redirect('/setting/display')->with('error', 'Data setting antarmuka tidak dapat diubah');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Display  $display
     * @return \Illuminate\Http\Response
     */
    public function destroy(Display $display)
    {
        $status = $display->status;
        if($status == 'aktif'){
            return redirect('/setting/display')->with('error', 'Data setting antarmuka tidak dapat dihapus!, karena masih aktif.');
        }

        $keterangan = $display->keterangan;
        if($keterangan=='local'){
            $deleteFile = Storage::delete('display/'.$display->source);
            if(!$deleteFile){
                return redirect('/setting/display')->with('error', 'Data file setting antarmuka tidak dapat dihapus');
            }
        }
        
        $destroy = Display::destroy($display->id);

        if($destroy){
            return redirect('/setting/display')->with('success', 'Data setting antarmuka telah berhasil dihapus');
        }else{
            return redirect('/setting/display')->with('error', 'Data setting antarmuka tidak dapat dihapus');
        }
    }

    public function aktifDisplay(Display $display)
    {
        $data['status'] = 'aktif';

        $display_sebelumnya = Display::where('status', $data['status'])->first();
        if(!$display_sebelumnya){
            $aktifDisplay = Display::where('id', $display->id)->update($data);
        }else{
            $change = Display::where('id', $display_sebelumnya->id)->update(['status'=>null]);
            if(!$change){
                return redirect('/setting/display')->with('error', 'Data setting antarmuka sebelumnya tidak dapat dikembalikan');
            }
            $aktifDisplay = Display::where('id', $display->id)->update($data);
        }

        if($aktifDisplay){
            return redirect('/setting/display')->with('success', 'Data status setting antarmuka telah berhasil diubah');
        }else{
            return redirect('/setting/display')->with('error', 'Data status setting antarmuka tidak dapat diubah');
        }
    }
}
