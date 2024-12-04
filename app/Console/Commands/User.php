<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use App\Models\User as UserModel;
use App\Models\Role;
use Illuminate\Support\Facades\Artisan;

class User extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create super admin user';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $this->info('Running seeders to pre-populate data...');
        Artisan::call('db:seed');

        $firstName = $this->ask('First Name');
        $middleName = $this->ask('Middle Name');
        $lastName = $this->ask('Last Name');
        $email = $this->ask('email');
        $password = $this->secret('password');
        $passwordConfirmation = $this->secret('password_confirmation');

        if (UserModel::where('email', $email)->exists()) {
            $this->error('Email already exist');
            return;
        }

        if ($password !== $passwordConfirmation) {
            $this->error('Password confirmation does not match');
            return;
        }

        $roles = Role::all();
        $roleNames = $roles->pluck('role')->toArray();

        $roleName = $this->choice('Choose a role for the user', $roleNames);

        $user = UserModel::create([
            'first_name' => $firstName,
            'middle_name' => $middleName,
            'last_name' => $lastName,
            'email' => $email,
            'password' => Hash::make($password),
            'role' => $roleName
        ]);

        $this->info('Super admin user created successfully!');
    }
}
