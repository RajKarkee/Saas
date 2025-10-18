<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use App\Models\SuperAdmin;

class CreateSuperAdmin extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'superadmin:create 
                            {name : The name of the super admin}
                            {email : The email of the super admin}
                            {password : The password of the super admin}';

    /**
     * The console command description.
     */
    protected $description = 'Create a new Super Admin account';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $email = $this->argument('email');
        $password = $this->argument('password');

        if (SuperAdmin::where('email', $email)->exists()) {
            $this->error("A Super Admin with this email already exists.");
            return 1;
        }

        $superAdmin = SuperAdmin::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        $this->info("✅ Super Admin created successfully!");
        $this->info("Name: {$superAdmin->name}");
        $this->info("Email: {$superAdmin->email}");

        return 0;
    }
}
