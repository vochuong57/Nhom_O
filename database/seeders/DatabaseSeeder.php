<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
//add thêm vài thư viện
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('user_catalogues')->insert([
            'name'=>'Quản trị viên',
        ]);

        DB::table('users')->insert([
            'email'=>'vochuong57@gmail.com',
            'password'=>Hash::make('123456'),
            'user_catalogue_id'=>'1'
        ]);
    }
}
