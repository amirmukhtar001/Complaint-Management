<?php

namespace Database\Seeders;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $array = [
            ['admin1','admin1@gmail.com','admin'],
            ['admin2','admin2@gmail.com','admin'],
            ['csr1','csr1@gmail.com','customer'],
            ['csr2','csr2@gmail.com','customer'],
            ['csr3','csr3@gmail.com','customer'],
            ['csr4','csr4@gmail.com','customer'],
            ['csr5','csr5@gmail.com','customer'],

        ];
        foreach ($array as $key => $value){
            $array2[] = [
                'name'  => $value[0],
                'email' => $value[1],
                'role'  => $value[2],
                'phone'=>'+923359198738',
                'password' => bcrypt('password'),
                'email_verified_at' => Carbon::now()->toDateTimeString(),
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ];
        }
        DB::table('users')->insert($array2);
    }
}
