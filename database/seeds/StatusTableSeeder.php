<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['name' => 'Generada'],
            ['name' => 'Ejecutada'],
            ['name' => 'Programada']
         ];
        DB::table('status')->insert($data);
    }
}
