<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CreateUser extends Command
{
    protected $signature = 'user:create
                            {name : Full name of the user}
                            {email : Email address used to log in}
                            {--role=staff : Role of the user (admin|staff)}
                            {--job-title= : Job title, e.g. Event Director}
                            {--password= : Password (auto-generated if omitted)}';

    protected $description = 'Create a new staff account. There is no public registration.';

    public function handle(): int
    {
        $role = $this->option('role');

        if (! in_array($role, ['admin', 'staff'], true)) {
            $this->error("Role must be 'admin' or 'staff'.");

            return self::FAILURE;
        }

        $password = $this->option('password') ?: Str::password(12);

        User::create([
            'name' => $this->argument('name'),
            'email' => $this->argument('email'),
            'password' => $password,
            'role' => $role,
            'job_title' => $this->option('job-title'),
        ]);

        $this->info("User {$this->argument('email')} created.");

        if (! $this->option('password')) {
            $this->warn("Generated password: {$password}");
        }

        return self::SUCCESS;
    }
}
