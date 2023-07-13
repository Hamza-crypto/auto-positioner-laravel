<?php

namespace Database\Seeders;

use App\Models\CSVHeader;
use App\Models\Position;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {

        $employees = [
            [
                'name' => 'John',
                'age' => 16,
                'time_in' => '08:00:00',
                'time_out' => '02:00:00',
                'begin_break' => '12:30:00',
                'finish_break' => '01:15:00',
                'positions' => '1'
            ],
            [
                'name' => 'Alicia',
                'age' => 19,
                'time_in' => '08:00:00',
                'time_out' => '04:00:00',
                'begin_break' => '12:45:00',
                'finish_break' => '01:30:00',
                'positions' => '1,2,3'
            ],
            [
                'name' => 'Jack',
                'age' => 24,
                'time_in' => '09:00:00',
                'time_out' => '03:00:00',
                'begin_break' => '12:10:00',
                'finish_break' => '12:40:00',
                'positions' => '1,3'
            ],
            [
                'name' => 'Davis',
                'age' => 20,
                'time_in' => '09:00:00',
                'time_out' => '03:00:00',
                'begin_break' => '12:10:00',
                'finish_break' => '12:40:00',
                'positions' => '1,3'
            ],
            [
                'name' => 'Emily',
                'age' => 22,
                'time_in' => '09:00:00',
                'time_out' => '05:00:00',
                'begin_break' => '01:20:00',
                'finish_break' => '01:50:00',
                'positions' => '1,3,5'
            ],
        ];

        foreach ($employees as $employee) {
            User::create($employee);
        }

        $positions = [
            'Register',
            'Runner',
            'Fryer',
            'Sandwich Designer',
            'Mobile Order',
            'Griller',
            'Backline Cook'
        ];

        foreach ($positions as $position) {
            Position::create([
                'name' => $position
                ]);
        }

    }
}
