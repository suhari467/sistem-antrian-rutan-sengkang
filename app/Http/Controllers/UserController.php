<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Models\Loket;
use App\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();

        $data = [
            'title' => 'Data Pengguna',
            'slug' => 'user',
            'users' => $users
        ];

        return view('users.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data_loket = Loket::all();
        $roles = Role::all();

        $data = [
            'title' => 'Tambah Data Pengguna',
            'slug' => 'user',
            'data_loket' => $data_loket,
            'roles' => $roles
        ];

        return view('users.create', $data);
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
            'username' => 'required|alpha_dash|unique:users|min:6|max:255',
            'password' => 'required|min:8|max:255',
            'loket_id' => 'required',
            'role_id' => 'required'
        ]);
        
        $validate['password'] = bcrypt($request->password);
        
        // ddd($validate);
        $create = User::create($validate);

        if($create){
            return redirect('/user')->with('success', 'Data telah berhasil ditambah');
        }else{
            return redirect('/user')->with('error', 'Data gagal ditambah. Harap coba kembali');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $data_loket = Loket::all();
        $roles = Role::all();

        $data = [
            'title' => 'Edit Data Pengguna',
            'slug' => 'user',
            'user' => $user,
            'data_loket' => $data_loket,
            'roles' => $roles
        ];

        return view('users.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $rules = [
            'name' => 'required|max:255',
            'loket_id' => 'required',
            'role_id' => 'required'
        ];

        $rules['username'] = $request->username == $user->username ? 'required' : 'required|unique:users';

        $validate = $request->validate($rules);

        $update = User::where('id', $user->id)->update($validate);

        if($update){
            return redirect('/user')->with('success', 'Data telah berhasil diubah');
        }else{
            return redirect('/user')->with('error', 'Data gagal diubah. Harap coba kembali');
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $destroy = User::destroy($user->id);
        if($destroy){
            return redirect('/user')->with('success', 'Data pengguna telah berhasil dihapus');
        }else{
            return redirect('/user')->with('error', 'Data pengguna tidak dapat dihapus');
        }
    }

    public function password(User $user)
    {
        $data = [
            'title' => 'Reset Password',
            'slug' => 'user',
            'user' => $user
        ];

        return view('users.password', $data);
    }

    public function password_verification(Request $request, User $user)
    {
        $validate = $request->validate([
            'new_password' => 'required|min:8|max:255',
            'repeat_password' => 'same:new_password'
        ]);

        $data['password'] = Hash::make($validate['new_password']);

        $update_password = User::where('id', $user->id)->update($data);
        if($update_password){
            return redirect('/user')->with('success', 'Data password pengguna telah berhasil diubah');
        }else{
            return redirect('/user')->with('error', 'Data password pengguna tidak dapat diubah. Harap coba kembali');
        }

    }
}
