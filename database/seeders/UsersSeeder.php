<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Type\Integer;
use Ramsey\Uuid\Uuid;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [];

        $faker = Faker::create('id_ID');
        
        for ($i=0; $i < 10; $i++) { 
            array_push($data,[
                'id' => Uuid::uuid4()->toString(),
                'nik' => $faker->nik,
                'name' => $faker->name,
                'email' => $faker->email,
                'departement' => 'Departement' . $i,
                'password' => Hash::make('password'),
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s'),
            ]);
        }
        DB::table('users')->insert($data);
    }
}
