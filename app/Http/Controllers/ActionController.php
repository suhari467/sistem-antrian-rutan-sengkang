<?php

namespace App\Http\Controllers;

use App\Models\Action;
use App\Models\Queue;
use Illuminate\Http\Request;

class ActionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $nomor_antrian = $request->nomor_antrian;
        $antrian = Queue::where('nomor_antrian', $nomor_antrian)->first();

        $data = [
            'nomor_antrian' => $antrian->nomor_antrian,
            'purpose_id' => $antrian->purpose_id,
            'nomor_loket' => $antrian->nomor_loket
        ];

        $create = Action::create($data);
        
        if($create){
            $data['status_code'] = 100;
            return json_encode($data);
        }else{
            return json_encode(['status_code' => 200]);
        }
    }

    public function get()
    {
        $antrian = Action::orderBy('created_at', 'asc')->first();

        
        if($antrian){
            $data = [
                'nomor_antrian' => $antrian->nomor_antrian,
                'nomor_loket' => $antrian->nomor_loket,
                'status_code' => 100
            ];
            
            return json_encode($data);
        }else{
            return json_encode(['status_code' => 200]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Action  $action
     * @return \Illuminate\Http\Response
     */
    public function show(Action $action)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Action  $action
     * @return \Illuminate\Http\Response
     */
    public function edit(Action $action)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Action  $action
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Action $action)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Action  $action
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $nomor_antrian = $request->nomor_antrian;
        $antrian = Action::where('nomor_antrian', $nomor_antrian)->first();

        $destroy = Action::destroy($antrian->id);
        
        if($destroy){
            return json_encode(['status_code' => 100]);
        }else{
            return json_encode(['status_code' => 200]);
        }
    }
}
