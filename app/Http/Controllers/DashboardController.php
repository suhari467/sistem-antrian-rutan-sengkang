<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Queue;
use App\Models\Loket;
use App\Models\Purpose;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $queue_count = Queue::all()->count();
        $loket_count = Loket::all()->count();
        $purpose = Purpose::all();
        $purpose_count = $purpose->count();
        $user_count = User::all()->count();
        
        $no = 1;
        foreach ($purpose as $purposed){
            $check = DB::table('queues')->where('queues.purpose_id', $purposed->id)->first();
            
            if($check){
                $queue = DB::table('queues')
                            ->join('purposes', 'queues.purpose_id', '=', 'purposes.id')
                            ->select('queues.*', 'purposes.*')
                            ->where('queues.purpose_id', $purposed->id)
                            ->get();
                // ddd($queue);

                $queue_first[$no] = collect($queue)->first();
                $queue_last[$no] = collect($queue)->last();
            }else{
                $queue_first[$no] = [];
                $queue_last[$no] = [];
            }

            $no++;
        }

        $data = [
            'title' => 'Dashboard',
            'slug' => 'dashboard',
            'queue_count' => $queue_count,
            'loket_count' => $loket_count,
            'purpose_count' => $purpose_count,
            'user_count' => $user_count,
            'queue_first' => $queue_first,
            'queue_last' => $queue_last
        ];
        // ddd($data);
        return view('dashboard.index', $data);
    }

    public function reset(Request $request)
    {
        $user = User::where('id', $request->user_id)->first();
        $role = $user->role->name;
        if($role!='admin'){
            return redirect('/dashboard')->with('error', 'Antrian gagal dikosongkan. Harap login admin');
        }
        
        $truncate = Queue::truncate();
        if($truncate){
            return redirect('/dashboard')->with('success', 'Antrian berhasil dikosongkan');
        }else{
            return redirect('/dashboard')->with('error', 'Antrian gagal dikosongkan');
        }
    }
}
