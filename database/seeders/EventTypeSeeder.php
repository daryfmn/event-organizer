<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('event_types')->insert([
        ['name' => 'Running Event', 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Corporate Event', 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Concert Event', 'created_at' => now(), 'updated_at' => now()],
    ]);
    }
}
