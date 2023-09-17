<?php

namespace Database\Seeders;

use App\Models\Position;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run()
    {

        $employees = [
            [
                'name' => 'John',
                'age' => 16,
            ],
            [
                'name' => 'Alicia',
                'age' => 19,
            ],
            [
                'name' => 'Jack',
                'age' => 24,
            ],
            [
                'name' => 'Davis',
                'age' => 20,
            ],
            [
                'name' => 'Emily',
                'age' => 22,
            ],
        ];

        foreach ($employees as $employee) {

            $employee_id = User::create($employee)->id;
            $limit = rand(1, 3);
            for ($i = 0; $i < $limit; $i++) {
                $data = ['user_id' => $employee_id, 'position_id' => rand(1, 7)];
                DB::table('user_positions')->insert($data);
            }

        }

        $positions = [
            ['Register', 5],
            ['Runner', 5],
            ['Fryer', 2],
            ['Griller', 1],
            ['Mobile Order', 2],
            ['Backline Cook', 1],
            ['Sandwich Designer', 1],
        ];

        foreach ($positions as $position) {
            Position::create([
                'name' => $position[0],
                'count' => $position[1],
            ]);
        }

    }
}
