<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Gunakan lokalisasi Indonesia untuk nama dan telepon yang realistis
        $faker = Faker::create('id_ID');

        $departments = [
            'Human Resources',
            'Finance & Accounting',
            'Information Technology',
            'Marketing',
            'Sales',
            'Operations',
            'Legal & Compliance',
            'Engineering',
            'Product Management',
            'Creative Design',
            'Customer Service',
            'Quality Assurance',
            'Procurement',
            'Security & Facilities'
        ];

        $this->command?->info('Seeding 500 employees...');

        for ($i = 0; $i < 500; $i++) {
            Employee::create([
                'employee_code' => 'EMP-' . str_pad($i + 1, 3, '0', STR_PAD_LEFT),
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'department' => $departments[array_rand($departments)],
                'phone' => $faker->phoneNumber,
            ]);
        }

        $this->command?->info('Seeding 500 employees completed successfully!');
    }
}
