<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Rap2hpoutre\FastExcel\FastExcel;
use OpenSpout\Common\Entity\Style\Style;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Models\Position;
use App\Models\User;
use Carbon\Carbon;



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

    public function displayEmployeeSchedule(Request $request)
    {
        //$employeeData = $request->json()->all();
        $employeeData = $request->json('employeeData');
        $scheduleStartTime = $request->json('scheduleStartTime');
        $scheduleEndTime = $request->json('scheduleEndTime');

        //............................/\/\/\/\/\/\/\/\/\/\/\/\/\/\.............................
        //........................adding break window for each employee........................
        //............................/\/\/\/\/\/\/\/\/\/\/\/\/\/\.............................

        $processedData = [];
        foreach ($employeeData as $employee) {
            $employeeId = $employee['employeeId'];
            $employeeName = $employee['employeeName'];
            $startTime = $employee['startTime'];
            $endTime = $employee['endTime'];
            $positions = $employee['positions'];
            $employeeAge = $employee['employeeAge'];
            $PositionPriorty = $employee['cardBodyId'];

            $breakWindow = $this->calculateBreakWindow($startTime, $endTime, $employeeAge);
            $processedData[] = [
                'employeeId' => $employeeId,
                'employeeName' => $employeeName,
                'startTime' => $startTime,
                'endTime' => $endTime,
                'positions' => $positions,
                'PositionPriorty' => $PositionPriorty,
                'employeeAge' =>  $employeeAge,
                'breakWindow' => $breakWindow,

            ];
        }

        //........................../\/\/\/\/\/\/\/\/\/\/\/\/\/\............................
        //.............................PREPAIRING THE SCHEDULE..............................
        //........................../\/\/\/\/\/\/\/\/\/\/\/\/\/\............................


        $scheduleData = new Collection();

        // Add header row
        $positions = Position::all();
        $headerRow = ['Time'];
        foreach ($positions as $position) {
            if ($position->count === 1) {
                $headerRow[] = $position->name;
            } else {
                for ($i = 1; $i <= $position->count; $i++) {
                    $headerRow[] = $position->name . ' ' . $i;
                }
            }
        }

        $scheduleData->push($headerRow);

        // Split the time strings into hours and minutes
        list($startHour, $startMinute) = explode(':', $scheduleStartTime);
        list($endHour, $endMinute) = explode(':', $scheduleEndTime);

        // Create Carbon instances
        $startTime = Carbon::createFromTime($startHour, $startMinute);
        $endTime = Carbon::createFromTime($endHour, $endMinute);
        $timeSlot = 15; // Time slot in minutes

        while ($startTime <= $endTime) {
            $timeSlotFormatted = $startTime->format('H:i');
            $scheduleData->push([$timeSlotFormatted]);
            $startTime->addMinutes($timeSlot);
        }


        // filling null to every cell
        foreach ($scheduleData as $rowIndex => &$rowData) {
            if ($rowIndex === 0) {
                continue;
            }
            for ($columnIndex = 1; $columnIndex < count($headerRow); $columnIndex++) {
                $rowData[$columnIndex] = null;
            }
            $scheduleData[$rowIndex] = $rowData;
        }


        //..................................../\/\/\/\/\/\/\/\/\/\/\/\/\/\............................................
        // ...................................STARTING SCHEDULE POPULATION............................................
        //..................................../\/\/\/\/\/\/\/\/\/\/\/\/\/\............................................

        //initializing 2D break array having data of employees on break
        $breakArray = [];
        //waiting que for new employees that are not placed on ny position
        $waitingQue = [];

        foreach ($scheduleData as $rowIndex => $rowData) {

            // Skip the first row header
            if ($rowIndex === 0) {
                continue;
            }

            //get the time slot value for each row in the schedule
            $timeSlotValue = $rowData[0];
            $timeSlotValueformatted = Carbon::createFromFormat('H:i', $timeSlotValue);


            //.................................................................................
            //................ Iterate over processed data for the new employee................
            //.................................................................................


            foreach ($processedData as $employee) {
                $employeeId = $employee['employeeId'];
                $positions = $employee['positions'];
                $startTime = Carbon::createFromFormat('H:i', $employee['startTime']);
                $endTime = Carbon::createFromFormat('H:i', $employee['endTime']);
                $PositionPriorty = $employee['PositionPriorty'];

                //check if there is a new employee
                if ($timeSlotValueformatted->equalTo($startTime)) {

                    $empPlacedOnPriortyPosition = False;
                    $empPlacedOnAnyTrainedPosition = False;
                    $empPlacedOnMinerPosition = False;
                    $employeePlacedBySwaping = false;

                    //Option 1:..........Place the new employee in the priorty position
                    $empPlacedOnPriortyPosition = $this->placeEmployeeInPriorityPosition(
                        $scheduleData,
                        $rowData,
                        $rowIndex,
                        $timeSlotValueformatted,
                        $PositionPriorty,
                        $employeeId,
                        $processedData,
                        $headerRow
                    );

                    //Option 2:..........place the new employee in the other trained positions if any 
                    if ($empPlacedOnPriortyPosition == False) {
                        $empPlacedOnAnyTrainedPosition = $this->placeEmployeeInOtherTrainedPositions(
                            $scheduleData,
                            $rowData,
                            $rowIndex,
                            $timeSlotValueformatted,
                            $positions,
                            $PositionPriorty,
                            $employeeId,
                            $processedData,
                            $headerRow,
                        );
                    }

                    //Option 3...........place the new employee after of a minor having break and related position
                    if ($empPlacedOnPriortyPosition == False && $empPlacedOnAnyTrainedPosition == False) {

                        $empPlacedOnMinerPosition = $this->placeEmployeeInMinorPosition(
                            $scheduleData,
                            $rowData,
                            $rowIndex,
                            $timeSlotValueformatted,
                            $positions,
                            $employeeId,
                            $breakArray,
                            $headerRow,
                            $processedData
                        );
                    }

                    //Option 4...........place the new employee by swapping any related position employee with minor or empty position
                    if ($empPlacedOnPriortyPosition == False && $empPlacedOnAnyTrainedPosition == False && $empPlacedOnMinerPosition == False) {
                        //find the trained positions
                        foreach ($headerRow as $columnIndex => $columnName) {
                            foreach ($positions as $positionName) {
                                //if position matches
                                if (strpos($columnName, $positionName) !== false) {
                                    //set the employeeToBeReplaced
                                    $previousRowData = $scheduleData[$rowIndex - 1];
                                    $previousCellValue = $previousRowData[$columnIndex];
                                    $currentCellValue = $rowData[$columnIndex];
                                    if ($currentCellValue == null) {
                                        $empToBeReplaced = $previousCellValue;
                                    } else {
                                        $empToBeReplaced =  $currentCellValue;
                                    }
                                    //get positions of the employeeToBeReplaced
                                    $empToBeReplacedPositions = [];
                                    $empToBeReplacedId = null;
                                    foreach ($processedData as $employee) {

                                        if ($empToBeReplaced == $employee['employeeId']) {
                                            $empToBeReplacedId = $employee['employeeId'];
                                            $empToBeReplacedPositions = $employee['positions'];
                                            break;
                                        }
                                    }
                                    //find the positions of the employeeTBeReplaced that are not in the new element
                                    foreach ($empToBeReplacedPositions as $differntPosition) {
                                        if (!in_array($differntPosition, $positions)) {
                                            foreach ($headerRow as $colIndex => $headerColName) {
                                                //if position in the header that matches the different position of employeeTBeReplaced
                                                if (strpos($headerColName, $differntPosition) !== false) {

                                                    //check if the current cell having employee of different position is empty
                                                    if ($rowData[$colIndex] == null) {

                                                        //check if the previous cell is also null
                                                        if ($scheduleData[$rowIndex - 1][$colIndex] == null) {
                                                            //place the employeeTBeReplaced here in the current row in this different position
                                                            $rowData[$colIndex] = $empToBeReplacedId;
                                                            //place new employee in the employeeTBeReplaced position that matches new employee position
                                                            $rowData[$columnIndex] = $employeeId;
                                                            $employeePlacedBySwaping = true;
                                                            break 4;
                                                        } else {
                                                            //if the previous cell is not empty access the employee of different position
                                                            $differentPosPrevEmp = $scheduleData[$rowIndex - 1][$colIndex];
                                                            //check for the end shift time of this employee of different position
                                                            $key = array_search($differentPosPrevEmp, array_column($processedData, 'employeeId'));
                                                            $difPosPrevEmpEndShiftTime = Carbon::createFromFormat('H:i', $processedData[$key]['endTime']);

                                                            //if end shift time of previous employee of different position has come
                                                            if ($difPosPrevEmpEndShiftTime->equalTo($timeSlotValueformatted)) {
                                                                //place the previous employee here in the current row in this different position
                                                                $rowData[$colIndex] = $empToBeReplacedId;
                                                                //place new employee in the previous employee position that matches new employee pposition
                                                                $rowData[$columnIndex] = $employeeId;
                                                                $employeePlacedBySwaping = true;
                                                                break 4;
                                                            }
                                                            //check if break window for previous employee of different position has began
                                                            $difPosPrevEmpWindowStartTime = $processedData[$key]['breakWindow']['windowStart'];
                                                            $difPosPrevEmpPriortyPosition = $processedData[$key]['PositionPriorty'];
                                                            $difPosPrevEmpAllPositions = $processedData[$key]['positions'];

                                                            if ($difPosPrevEmpWindowStartTime != null) {
                                                                $difPosPrevEmpWindowStartTime = Carbon::createFromFormat('H:i', $difPosPrevEmpWindowStartTime);
                                                                if (
                                                                    $difPosPrevEmpWindowStartTime->lessThanOrEqualTo($timeSlotValueformatted) &&
                                                                    !isset($breakArray[$difPosPrevEmpWindowStartTime])
                                                                ) {
                                                                    //store the previous employee of different position in the $breakArray
                                                                    $breakDetails = [
                                                                        'breakStart' => $timeSlotValueformatted->format('H:i'),
                                                                        'breakEnd' => null,
                                                                        'positionOccupied' => $headerRow[$colIndex],
                                                                        'allPositions' => $difPosPrevEmpAllPositions,
                                                                        'PriortyPosition' => $difPosPrevEmpPriortyPosition,
                                                                    ];
                                                                    $breakArray[$differentPosPrevEmp] = $breakDetails;

                                                                    //place the previous employee here in the current row in this different position
                                                                    $rowData[$colIndex] = $empToBeReplacedId;
                                                                    //place new employee in the previous employee position that matches new employee pposition
                                                                    $rowData[$columnIndex] = $employeeId;
                                                                    $employeePlacedBySwaping = true;
                                                                    break 4;
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }

                    //Option 5...........place the new employee in the que
                    if ($empPlacedOnPriortyPosition == False && $empPlacedOnAnyTrainedPosition == False && $empPlacedOnMinerPosition == False &&  $employeePlacedBySwaping == false) {
                        $waitingQue[] = $employee;
                    }
                }
            } //ends new employee code section


            //.................................................................................
            //................. Now check the employees in the break window ...................
            //.................................................................................  


            foreach ($breakArray as $key => $value) {
                $MinorId = $key;
                $breakEnd = $value['breakEnd'];
                $employeeInitialPosition = $value['positionOccupied'];
                $PositionPriorty = $value['PriortyPosition'];
                $positions = $value['allPositions'];

                //if minor is still on break
                if ($breakEnd == null) {
                    $breakStart = Carbon::createFromFormat('H:i', $value['breakStart']);
                    $durationInMinutes = $breakStart->diffInMinutes($timeSlotValueformatted);
                    //if break time is completed
                    if ($durationInMinutes >= 45) {


                        //.................place the minor back in the schedule after break end.........................

                        $minorPlacedInInitialPosition = false;
                        $minorPlacedInPriortyPosition = false;
                        $minorPlacedInAnyTrainedPosition = false;
                        $minorPlacedAfterMinor = false;


                        //Option 1.........place in its initial position
                        $minorPlacedInInitialPosition = $this->placeEmployeeInPriorityPosition(
                            $scheduleData,
                            $rowData,
                            $rowIndex,
                            $timeSlotValueformatted,
                            $employeeInitialPosition,
                            $MinorId,
                            $processedData,
                            $headerRow

                        );
                        if ($minorPlacedInInitialPosition) {
                            //set minor breakEnd as current time
                            $breakArray[$MinorId]['breakEnd'] = $timeSlotValueformatted->format('H:i');
                        }


                        //Option 2.........place in the priorty position
                        if ($minorPlacedInInitialPosition == false) {
                            $minorPlacedInPriortyPosition = $this->placeEmployeeInPriorityPosition(
                                $scheduleData,
                                $rowData,
                                $rowIndex,
                                $timeSlotValueformatted,
                                $PositionPriorty,
                                $MinorId,
                                $processedData,
                                $headerRow

                            );
                            if ($minorPlacedInPriortyPosition) {
                                //set minor breakEnd as current time
                                $breakArray[$MinorId]['breakEnd'] = $timeSlotValueformatted->format('H:i');
                            }
                        }


                        //Option 3.........place in the any trained position
                        if ($minorPlacedInInitialPosition == false && $minorPlacedInPriortyPosition == false) {
                            $minorPlacedInAnyTrainedPosition = $this->placeEmployeeInOtherTrainedPositions(
                                $scheduleData,
                                $rowData,
                                $rowIndex,
                                $timeSlotValueformatted,
                                $positions,
                                $PositionPriorty,
                                $MinorId,
                                $processedData,
                                $headerRow,
                            );
                            if ($minorPlacedInAnyTrainedPosition) {
                                //set minor breakEnd as current time
                                $breakArray[$MinorId]['breakEnd'] = $timeSlotValueformatted->format('H:i');
                            }
                        }


                        //Option 4.........place after a minor having break window started
                        if ($minorPlacedInInitialPosition == false && $minorPlacedInPriortyPosition == false && $minorPlacedInAnyTrainedPosition == false) {
                            $minorPlacedAfterMinor = $this->placeEmployeeInMinorPosition(
                                $scheduleData,
                                $rowData,
                                $rowIndex,
                                $timeSlotValueformatted,
                                $positions,
                                $MinorId,
                                $breakArray,
                                $headerRow,
                                $processedData
                            );
                            if ($minorPlacedAfterMinor) {
                                //set minor breakEnd as current time
                                $breakArray[$MinorId]['breakEnd'] = $timeSlotValueformatted->format('H:i');
                            }
                        }
                    }
                }
            }


            //.................................................................................
            //....................default propagation of employee schedule.....................
            //.................................................................................


            // skip the first 2 rows
            if ($rowIndex >= 2) {
                foreach ($rowData as $colIndex => $columnValue) {
                    //check if the column value is empty
                    if ($columnValue === null) {
                        $previousRowData = $scheduleData[$rowIndex - 1];
                        $previousColumnValue = $previousRowData[$colIndex];

                        //if previous column value is not empty
                        if ($previousColumnValue != null) {
                            //access the end shift time and other details to fill the break array of the previous employee
                            $previousEmployeeEndTime = null;
                            $previousEmpBreakWindow = null;
                            $previousEmpPriortyPosition = null;
                            $previousEmpAllPositions = null;
                            foreach ($processedData as $employee) {

                                if ($previousColumnValue == $employee['employeeId']) {
                                    $prevEmpId = $employee['employeeId'];
                                    $previousEmployeeEndTime = Carbon::createFromFormat('H:i', $employee['endTime']);
                                    $previousEmpBreakWindow = $employee['breakWindow'];
                                    $previousEmpPriortyPosition = $employee['PositionPriorty'];
                                    $previousEmpAllPositions = $employee['positions'];
                                }
                            }
                            //if end shift time of the previous employee has not come
                            if ($previousEmployeeEndTime->greaterThan($timeSlotValueformatted)) {
                                //if previous employee is hving not null break window
                                if ($previousEmpBreakWindow['windowStart'] != null) {
                                    $previousEmpWindowStart = Carbon::createFromFormat('H:i', $previousEmpBreakWindow['windowStart']);
                                    // having a break window started
                                    if (
                                        $previousEmpWindowStart->lessThanOrEqualTo($timeSlotValueformatted) &&
                                        !isset($breakArray[$previousColumnValue])
                                    ) {
                                        //step1 store the previous employee in the $breakArray
                                        $breakDetails = [
                                            'breakStart' => $timeSlotValueformatted->format('H:i'),
                                            'breakEnd' => null,
                                            'positionOccupied' => $headerRow[$colIndex],
                                            'allPositions' => $previousEmpAllPositions,
                                            'PriortyPosition' => $previousEmpPriortyPosition,
                                        ];

                                        $breakArray[$prevEmpId] = $breakDetails;
                                    } else {
                                        //if no break window started or the break is granted, propagate as it is
                                        $rowData[$colIndex] = $previousColumnValue;
                                    }
                                } else {
                                    //if no break window, propagate as it is
                                    $rowData[$colIndex] = $previousColumnValue;
                                }
                            }
                        }
                    }
                }
            }


            // Update each row data in the scheduleData collection
            $scheduleData[$rowIndex] = $rowData;
        }


        //..................................replace ids with name in the schedule...............................


        foreach ($scheduleData as $rowIndex => $row) {

            foreach ($row as $valueIndex => $value) {

                if ($value === null) {
                    continue;
                }

                foreach ($processedData as $employee) {
                    if ($employee['employeeId'] == $value) {
                        $row[$valueIndex] = $employee['employeeName'];
                    }
                }
            }
            $scheduleData[$rowIndex] = $row;
        }

        //.............................. Return the data as a response to the AJAX call..........................

        return response()->json([
            'message' => 'Form data received and processed successfully',
            'processedData' => $processedData,
            'schedule' => $scheduleData,
            'breakArray'  => $breakArray,
            'waitingQue'  => $waitingQue,
        ]);
    }

    /**
     * Calculate the break window based on employee's age and shift duration.
     *
     * @param string $startTime The start time of the shift in "H:i" format.
     * @param string $endTime The end time of the shift in "H:i" format.
     * @param int $employeeAge The age of the employee.
     * @return array
     */
    protected function calculateBreakWindow($startTime, $endTime, $employeeAge)
    {
        $start = Carbon::createFromFormat('H:i', $startTime);
        $end = Carbon::createFromFormat('H:i', $endTime);


        // Calculate the total shift time
        $totalShiftTime = $start->diff($end)->h;

        // If shift time is smaller than or equal to 4 hours or not a minor, return 'nobreak'
        if ($totalShiftTime <= 4 || $employeeAge >= 18) {
            return [
                'totalShiftTime' => $totalShiftTime . ':00',
                'windowStart' => null,
                'windowEnd' => null,
            ];
        }

        // if employee is a minor and shift time is greater than 4
        if ($employeeAge < 18 && $totalShiftTime > 4) {
            // Add 3 hours to the start time
            $breakStart1 = $start->copy()->addHours(3)->format('H:i');

            // Add 4 hours and 30 minutes to the start time
            $breakStart2 = $start->copy()->addHours(4)->addMinutes(30)->format('H:i');

            return [
                'totalShiftTime' => $totalShiftTime . ':00',
                'windowStart' => $breakStart1,
                'windowEnd' => $breakStart2,
            ];
        }
    }


    private function placeEmployeeInPriorityPosition(
        $scheduleData,
        &$rowData,
        $rowIndex,
        $timeSlotValueformatted,
        $PositionPriorty,
        $employeeId,
        $processedData,
        $headerRow

    ) {
        foreach ($headerRow as $columnIndex => $columnName) {

            //if priorty position found
            if (strpos($columnName, $PositionPriorty) !== false) {

                //if its the second row and cell is empty
                if ($rowIndex === 1 && $rowData[$columnIndex] === null) {
                    $rowData[$columnIndex] = $employeeId;
                    return True;
                } else {
                    //if its not the second row and cell is empty
                    if ($rowIndex !== 1 && $rowData[$columnIndex] === null) {
                        $previousRowData = $scheduleData[$rowIndex - 1];

                        // if previous cell is also empty
                        if ($previousRowData[$columnIndex] === null) {
                            $rowData[$columnIndex] = $employeeId;
                            return True;
                        } else
                        //if the previous cell is not empty
                        {
                            //access the end shift time of the previus employee
                            $previousEmployeeId = $previousRowData[$columnIndex];
                            foreach ($processedData as $employee) {
                                $prevemployeeId = $employee['employeeId'];
                                if ($previousEmployeeId == $prevemployeeId) {
                                    $previousEmployeeEndTime = Carbon::createFromFormat('H:i', $employee['endTime']);
                                }
                            }
                            //if previous employee end shift time has come, place the new employee
                            if ($previousEmployeeEndTime->equalTo($timeSlotValueformatted)) {
                                $rowData[$columnIndex] = $employeeId;
                                return True;
                            }
                        }
                    }
                }
            }
        }
        return false;
    }

    private function placeEmployeeInOtherTrainedPositions(
        $scheduleData,
        &$rowData,
        $rowIndex,
        $timeSlotValueformatted,
        $positions,
        $PositionPriorty,
        $employeeId,
        $processedData,
        $headerRow,
    ) {
        //check if employee has more than one positions 
        $positionsCount = count($positions);
        if ($positionsCount > 1) {
            foreach ($headerRow as $columnIndex => $columnName) {
                foreach ($positions as $positionIndex => $positionName) {
                    if ($positionName == $PositionPriorty) {
                        continue;
                    }
                    //if position matches
                    if (strpos($columnName, $positionName) !== false) {

                        //if its the second row and cell is empty
                        if ($rowIndex === 1 && $rowData[$columnIndex] === null) {
                            $rowData[$columnIndex] = $employeeId;
                            return True;
                        } else

                            //if its not the second row and cell is empty
                            if ($rowIndex !== 1 && $rowData[$columnIndex] === null) {
                                $previousRowData = $scheduleData[$rowIndex - 1];

                                // if previous cell is also empty
                                if ($previousRowData[$columnIndex] === null) {
                                    $rowData[$columnIndex] = $employeeId;
                                    return True;
                                } else
                                //if the previous cell is not empty
                                {
                                    //access the end shift time of the previus employee
                                    $previousEmployeeId = $previousRowData[$columnIndex];
                                    foreach ($processedData as $employee) {
                                        $prevemployeeId = $employee['employeeId'];
                                        if ($previousEmployeeId == $prevemployeeId) {
                                            $previousEmployeeEndTime = Carbon::createFromFormat('H:i', $employee['endTime']);
                                        }
                                    }
                                    //if previous employee end shift time has come, place the new employee
                                    if ($previousEmployeeEndTime->equalTo($timeSlotValueformatted)) {
                                        $rowData[$columnIndex] = $employeeId;
                                        return True;
                                    }
                                }
                            }
                    }
                }
            }
            return false;
        } else {
            return false;
        }
    }

    private function placeEmployeeInMinorPosition(
        $scheduleData,
        &$rowData,
        $rowIndex,
        $timeSlotValueformatted,
        $positions,
        $employeeId,
        &$breakArray,
        $headerRow,
        $processedData
    ) {
        //to find the employee having break window started
        foreach ($rowData as $colIndex => $colItem) {
            //if cell is empty
            if ($colItem === null) {
                //get the current column position
                $columnPosition = $headerRow[$colIndex];
                $prevEmpBreakWindow = null;
                $prevEmployeeEndTime = null;
                $prevEmpAllPositions = null;
                $prevEmpPriortyPosition = null;
                foreach ($positions as $position) {
                    //if position matches with any position of the new employee
                    if (strpos($columnPosition, $position) !== false) {
                        //then check if previous employee id exists
                        $previousRow = $scheduleData[$rowIndex - 1];
                        $previousEmpId = $previousRow[$colIndex];
                        if ($previousEmpId != null) {
                            //get break window of previous employee
                            foreach ($processedData as $emp) {
                                if ($emp['employeeId'] == $previousEmpId) {
                                    $prevEmpBreakWindow = $emp['breakWindow'];
                                    $prevEmployeeEndTime = $emp['endTime'];
                                    $prevEmpAllPositions = $emp['positions'];
                                    $prevEmpPriortyPosition = $emp['PositionPriorty'];
                                    break;
                                }
                            }
                            //check if previous employee is a minor, check for its break Window
                            //check if break window started
                            //check if the employee is not granted the break
                            //check if already break taken and completed
                            //check if its not the shift end time
                            if (
                                $prevEmpBreakWindow['windowStart'] !== null &&
                                Carbon::createFromFormat('H:i', $prevEmpBreakWindow['windowStart'])->lessThanOrEqualTo($timeSlotValueformatted)  &&
                                !isset($breakArray[$previousEmpId]) &&
                                Carbon::createFromFormat('H:i', $prevEmployeeEndTime)->greaterThan($timeSlotValueformatted)
                            ) {
                                //place the new employee in this position
                                $rowData[$colIndex] = $employeeId;

                                //store the previous employee in the $breakArray
                                $breakDetails = [
                                    'breakStart' => $timeSlotValueformatted->format('H:i'),
                                    'breakEnd' => null,
                                    'positionOccupied' => $columnPosition,
                                    'allPositions' => $prevEmpAllPositions,
                                    'PriortyPosition' => $prevEmpPriortyPosition,
                                ];

                                $breakArray[$previousEmpId] = $breakDetails;
                                return true;
                            }
                        }
                    }
                }
            }
        }
        return false;
    }

    // ...................................................................................................
    //............................code section to download the sheat.................................................
    //...................................................................................................

    public function downloadEmployeeSchedule(Request $request)
    {
        $sheat = $request->json()->all();

        $structuredSheat = $this->structure($sheat);

        //...........................styling...................................

        $header_style = (new Style())->setFontBold()->setFontSize(10)->setBackgroundColor("0000FF")->setFontColor("FFFFFF");

        $rows_style = (new Style())->setFontSize(12);

        $filePath = storage_path('app/temp/sheat.xlsx');
        $excelFile = new FastExcel($structuredSheat);
        $excelFile->export($filePath);
        $excelFile->download('sheat.xlsx');

        return response()->json([
            'message' => 'Form data received and processed successfully',
            'data' => $structuredSheat,

        ]);
    }


    private function structure($sheat)
    {
        // Get the maximum number of columns based on the header row
        $maxColumns = count($sheat[0]);

        // Iterate through the existing data to structure the data

        foreach ($sheat as $row) {
            // Initialize a new row with empty values for all columns
            $newRow = array_fill(0, $maxColumns, null);

            // Fill the new row with values from the existing row
            foreach ($row as $index => $value) {
                $newRow[$index] = $value;
            }

            // Add the new row to the structured data
            $structuredSheat[] = $newRow;
        }

        //preparing sheat for fast excel

        $header = $structuredSheat[0];
        $preparedSheat = [];
        foreach ($structuredSheat as $rowIndex => $row) {
            if ($rowIndex === 0) {
                continue;
            }
            $newRow = [];
            foreach ($row as $colIndex => $value) {
                $key = $header[$colIndex];
                $newRow[$key] = $value;
            }
            $preparedSheat[] = $newRow;
        }

        return $preparedSheat;
    }
}
