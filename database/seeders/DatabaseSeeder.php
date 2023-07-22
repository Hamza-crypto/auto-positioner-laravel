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
                'time_in' => '08:00:00',
                'time_out' => '17:00:00',
                'break_in' => '12:30:00',
                'break_out' => '13:15:00',
                'position_ids' => [1, 2],
            ],
            [
                'name' => 'Alicia',
                'age' => 19,
                'time_in' => '08:30:00',
                'time_out' => '16:00:00',
                'break_in' => '12:45:00',
                'break_out' => '13:30:00',
                'position_ids' => [2],
            ],
            [
                'name' => 'Jack',
                'age' => 24,
                'time_in' => '09:00:00',
                'time_out' => '15:00:00',
                'break_in' => '12:10:00',
                'break_out' => '12:40:00',
                'position_ids' => [2, 3],

            ],
            [
                'name' => 'Davis',
                'age' => 20,
                'time_in' => '09:30:00',
                'time_out' => '15:00:00',
                'break_in' => '12:10:00',
                'break_out' => '12:40:00',
                'position_ids' => [3, 4],
            ],
            [
                'name' => 'Emily',
                'age' => 22,
                'time_in' => '10:00:00',
                'time_out' => '17:00:00',
                'break_in' => '13:20:00',
                'break_out' => '13:50:00',
                'position_ids' => [5, 6],
            ],
        ];

        foreach ($employees as $employee) {
            $employee['position_ids'] = json_encode($employee['position_ids']);

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
