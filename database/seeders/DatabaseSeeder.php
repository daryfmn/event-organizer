<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Default company settings and event types
        $this->call([
            CompanySettingsSeeder::class,
            EventTypeSeeder::class,
        ]);

        // Create a default admin user for testing
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role'      => 'admin',
            'job_title' => 'Event Director',
        ]);
    }
}
