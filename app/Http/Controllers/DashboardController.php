<?php

namespace App\Http\Controllers;

use App\Models\Position;
use App\Models\User;
use Carbon\Carbon;
use DateTime;

class DashboardController extends Controller
{
    public function index()
    {
        $positions = Position::all();
        $employees = User::all();

        // $positions = ['Reg', 'Fryer', 'Hot Dog Griller', 'Griller', 'Sandwich Maker', 'Backline Cook', 'Mobile Order'];
        return view('pages.dashboard.index', get_defined_vars());
        // return view('pages.dashboard.table', get_defined_vars());
    }

    public function get_table()
    {
        $timelineArray = $this->create_time_array();

        $start_time = "08:00 AM";

        // $start_time = Carbon::parse($start_time)->toTimeString();

        $employees = User::all();
        $positions = Position::all();
        $positions_data = [];
        $response = '';

        $employee_count = 0;
        foreach ($positions as $position) {

            $employee_for_position = User::whereJsonContains('position_ids', $position->id)->get()->toArray();

            $requiredDateTime = DateTime::createFromFormat('h:i A', $start_time);

            usort($employee_for_position, function ($a, $b) use ($requiredDateTime) {
                $aTime = DateTime::createFromFormat('h:i A', $a['time_in']);
                $bTime = DateTime::createFromFormat('h:i A', $b['time_in']);
                return abs($aTime->getTimestamp() - $requiredDateTime->getTimestamp()) - abs($bTime->getTimestamp() - $requiredDateTime->getTimestamp());
            });

            $employee = $employee_for_position[$employee_count];
            $shift_start = $employee['time_in'];
            $shift_end = $employee['time_out'];
            $break_start = $employee['break_in'];
            $break_end = $employee['break_out'];

            $break_start = Carbon::createFromFormat('h:i a', $break_start);
            $break_end = Carbon::createFromFormat('h:i a', $break_end);
            $shift_start = Carbon::createFromFormat('h:i a', $shift_start);
            $shift_end = Carbon::createFromFormat('h:i a', $shift_end);

            foreach ($timelineArray as $time) {

                $tr = '<tr>';

                $tr .= '<td>' . $time . '</td>';
                try {

                    $time = Carbon::createFromFormat('h:i a', $time);
                    // dump($time, $break_start, $break_end);

                    if ($time >= $shift_start && $time <= $shift_end) {

                        if ($time >= $break_start && $time <= $break_end) {
                            $tr .= '<td style="background-color: #e49e9e;"> Break </td>';
                        } else {
                            $tr .= '<td>' . $employee['name'] . '</td>';
                        }

                    } else {

                        $tr .= '<td> Another Employee </td>';

                    }

                } catch (\Exception $e) {
                    // dd($time);

                    dd("Exception occurred while processing time:", $e->getMessage());
                }

                $tr .= '</tr>';
                $response .= $tr;
            }
            break;
            $positions_data[$position->name] = 5;

        }

        // $positions = ['Reg', 'Fryer', 'Hot Dog Griller', 'Griller', 'Sandwich Maker', 'Backline Cook', 'Mobile Order'];

        echo $response;

    }

    public function create_time_array()
    {
        $startTime = "08:00 am";
        $endTime = "06:00 pm";

        $start = Carbon::createFromFormat('h:i a', $startTime);
        $end = Carbon::createFromFormat('h:i a', $endTime);

        $timeArray = [];

        while ($start <= $end) {
            $timeArray[] = $start->format('h:i a');
            $start->addMinutes(15);
        }

        return $timeArray;
    }

    public function show_employee_timeline()
    {

    }
}
