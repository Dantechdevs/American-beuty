<?php


namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StaffUserSeeder extends Seeder
{
    public function run(): void
    {
        $staff = [
            [
                'name'      => 'Manager',
                'email'     => 'manager@americanbeauty.com',
                'role'      => 'manager',
                'password'  => 'password123',
            ],
            [
                'name'      => 'POS Operator',
                'email'     => 'pos@americanbeauty.com',
                'role'      => 'pos_operator',
                'password'  => 'password123',
            ],
            [
                'name'      => 'Delivery Personnel',
                'email'     => 'delivery@americanbeauty.com',
                'role'      => 'delivery',
                'password'  => 'password123',
            ],
        ];

        foreach ($staff as $member) {
            User::updateOrCreate(
                ['email' => $member['email']],
                [
                    'name'      => $member['name'],
                    'role'      => $member['role'],
                    'password'  => Hash::make($member['password']),
                    'is_active' => true,
                ]
            );
        }

        $this->command->info('Staff users created successfully.');
    }
}