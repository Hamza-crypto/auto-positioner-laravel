<?php

namespace App\Http\Controllers;

use App\Models\Position;
use App\Models\User;
use Illuminate\Http\Request;

class EmployeesController extends Controller
{
    public function index(Request $request)
    {
        $employees = User::all();
        $positions = Position::all()->pluck('name', 'id')->toArray();

        // return $positions;
        $employees = $employees->map(function ($employee) use ($positions) {
            $position_ids = json_decode($employee->position_ids);

            // Map position IDs to position names
            $position_names = array_map(function ($position_id) use ($positions) {
                return $positions[$position_id] ?? 'Unknown';
            }, $position_ids);

            return [
                'id' => $employee->id,
                'name' => $employee->name,
                // 'position_ids' => $position_ids,
                'positions' => $position_names,
            ];
        });

        return $employees;

    }
}
