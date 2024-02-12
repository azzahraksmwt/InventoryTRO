<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

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

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // $sadmin = User::create([
        //     'idPengguna' => 'admin-1',
        //     'namaPengguna' => 'mika',
        //     'kelas' => 'sa01',
        //     'nohp' => '089876',
        //     'angkatan' => '2021',
        //     'username' => 'mika',
        //     'password'=> bcrypt('mika'),
        //     'admin' => 'admin1',
        //     'role' => 'Admin',
        //     'remember_token' => Str::random(36),
        // ]);

        $sdosen = User::create([
            'idPengguna' => 'dosen-1',
            'namaPengguna' => 'sarah',
            'kelas' => 'sa01',
            'nohp' => '089876',
            'angkatan' => '2021',
            'username' => 'sarah',
            'password'=> bcrypt('sarah'),
            'admin' => 'admin1',
            'role' => 'Dosen',
            'remember_token' => Str::random(36),
        ]);

        $smahasiswa = User::create([
            'idPengguna' => 'mahasiswa-1',
            'namaPengguna' => 'laras',
            'kelas' => 'sa01',
            'nohp' => '089876',
            'angkatan' => '2021',
            'username' => 'laras',
            'password'=> bcrypt('laras'),
            'admin' => 'admin1',
            'role' => 'Mahasiswa',
            'remember_token' => Str::random(36),
        ]);
    }
}
