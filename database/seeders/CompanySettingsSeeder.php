<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanySettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('company_settings')->insert([
            'company_name' => 'PT. Harmoni Citra Nusantara',
            'address' => 'Jl K.H Wahid Hasyim No. 12D Lt.2 I Menteng - Jakarta Pusat 10340',
            'bank_name' => 'BNI',
            'bank_branch' => 'Kantor Cabang Menteng',
            'account_number' => '1277066037',
            'account_name' => 'PT. Harmoni Citra Nusantara',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
