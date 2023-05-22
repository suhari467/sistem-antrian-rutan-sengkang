<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Purpose;
use App\Models\Loket;
use App\Models\Outlet;
use App\Models\Display;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        Outlet::create([
            'name' => 'Nama Outlet',
            'address' => 'Alamat Outlet',
            'no_telp' => 'No.Telp Outlet',
            'status' => 'aktif'
        ]);

        Display::create([
            'name' => 'pemandangan alam',
            'keterangan' => 'youtube',
            'source' => '1LdS8b5ur7M',
            'status' => 'aktif'
        ]);

        Role::create([
            'name' => 'admin'
        ]);

        Role::create([
            'name' => 'user'
        ]);

        Purpose::create([
            'kode' => 'A',
            'jenis' => 'teller',
            'keterangan' => 'Teller'
        ]);

        Purpose::create([
            'kode' => 'B',
            'jenis' => 'service',
            'keterangan' => 'Customer Service'
        ]);
        
        Loket::create([
            'name' => 'Loket 1',
            'nomor' => 1,
            'purpose_id' => 1
        ]);

        User::create([
            'name' => 'Admin',
            'username' => 'admin123',
            'password' => bcrypt('admin123'),
            'role_id' => 1,
            'loket_id' => 1
        ]);
    }
}
