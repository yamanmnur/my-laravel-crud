<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid;

class BarangSeeder extends Seeder
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
            $min = 100000;
            $max = 999999;

            $new_code = rand($min,$max);

            array_push($data,[
                'id' => Uuid::uuid4()->toString(),
                'kode' => $new_code,
                'nama' => 'Barang - '. $i,
                'kuantiti' => 10,
                'lokasi' => 'L1-R1A-'.$i,
                'status' => '1',
                'satuan' => 'PAK',
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s'),
            ]);
        }
        DB::table('ref_barang')->insert($data);
    }
}
