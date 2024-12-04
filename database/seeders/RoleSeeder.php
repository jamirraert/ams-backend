<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rawJson = database_path('seeders/raw/role_raw_data.json');

        $jsonData = file_get_contents($rawJson);

        $roleData = json_decode($jsonData, true);

        $role = [];

        foreach($roleData as $data) {
            $data['created_at'] = now();
            $data['updated_at'] = now();

            $role[] = $data;
        }

        Role::insert($role);
    }
}
