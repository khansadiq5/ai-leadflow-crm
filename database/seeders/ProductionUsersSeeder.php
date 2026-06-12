<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class ProductionUsersSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'khansadiq123@gmail.com'],
            [
                'name' => 'Khan Sadiq',
                'password' => '$2y$12$V4Mxqe9.9Z1zC19twuxhiOC38/N1iGSzgRMtiMFlQonHRLE4nmwfu',
                'role' => 'admin',
                'status' => 'active',
            ]
        );

        User::updateOrCreate(
            ['email' => 'kartik123@gmail.com'],
            [
                'name' => 'kartik yadav',
                'password' => '$2y$12$aTyk2PeLm09NVkyBhmBI7ODqegXjqpPU3e2Q3Xu06LCKPIZIBKAd6',
                'role' => 'sales_executive',
                'status' => 'active',
            ]
        );

        User::updateOrCreate(
            ['email' => 'shivam123@gmail.com'],
            [
                'name' => 'Shivam Rai',
                'password' => '$2y$12$AKd4hG.rZuB/uJe7184Xa.tkHHO7uIMoCak5UpafKCm6JyJu7ZG4y',
                'role' => 'manager',
                'status' => 'active',
            ]
        );

        User::updateOrCreate(
            ['email' => 'rohit123@gmail.com'],
            [
                'name' => 'Rohit',
                'password' => '$2y$12$UTm.1H9moHaaeGu9AYLMe.COkktx.pKl6KjJcxZ.lj6HSowE3Ae7.',
                'role' => 'support_agent',
                'status' => 'active',
            ]
        );

        User::updateOrCreate(
            ['email' => 'khansadik5105@gmail.com'],
            [
                'name' => 'Amit Rathod',
                'password' => '$2y$12$QuI2uCe5HNsRI4gClxZ0AOs4lpVG05RNhTF5LL749enTgc0WnIlJC',
                'role' => 'sales_executive',
                'status' => 'active',
            ]
        );
    }
}