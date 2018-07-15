<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Religion_types_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('religion_type')->insert([
            'religionName' => 'Christianity',
            'order' => 1,
        ]);
        DB::table('religion_type')->insert([
            'religionName' => 'Hinduism',
            'order' => 2,
        ]);
        DB::table('religion_type')->insert([
            'religionName' => 'Judaism',
            'order' => 3,
        ]);
        DB::table('religion_type')->insert([
            'religionName' => 'Buddhism',
            'order' => 4,
        ]);
        DB::table('religion_type')->insert([
            'religionName' => 'Islam',
            'order' => 5,
        ]);
    }
}
