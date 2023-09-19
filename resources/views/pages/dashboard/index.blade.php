@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

@section('styles')
    <style>
        table {
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }

        .avatar.avatar-state-secondary:before {
            display: none;
        }
    </style>

    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-clockpicker.min.css') }}" type="text/css">
@endsection

@section('bundlingScripts')
<script src="{{ asset('assets/js/bootstrap-clockpicker.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

@endsection

@section('scripts')
    {{-- <script>
        $(document).ready(function() {
            $('#schedule-table').DataTable();
        });
    </script> --}}

    <script>
        $(document).ready(function() {
            var allEmployees = ".clockpicker-example";
            initializeClockpicker(allEmployees);
        });

        function initializeClockpicker(selector) {

            $(selector).clockpicker({
                donetext: 'Done'
            });

            var selectedEmployeeId = null;


            //...............handle the time entering and appending.................


            $(selector).on('change', function() {

                //accessing list group item
                const $employeeItem = $(this).closest('.list-group-item');

                //accessing employee id
                selectedEmployeeId = $(this).closest('.list-group-item').attr('id');
                console.log(selectedEmployeeId);

                //accessing employee start time
                var startTime = $('[name="time_in_' + selectedEmployeeId + '"]').val();
                console.log(startTime);

                //accessing employee end time
                var endTime = $('[name="time_out_' + selectedEmployeeId + '"]').val();
                console.log(endTime);

                //if both end and start time is set, append the element
                if (startTime && endTime) {

                    const firstPositionName = $employeeItem.find('[data-position-name]:first').data(
                        'position-name');
                    console.log("first position of this employee is: " + firstPositionName);
                    // Remove spaces from firstPositionName
                    const cleanedFirstPositionName = firstPositionName.replace(/\s+/g, '');
                    const matchingElement = document.getElementById(cleanedFirstPositionName);

                    if (matchingElement) {

                        console.log(`Element with ID '${cleanedFirstPositionName}' exists.`);
                        //clone the anchor element to be appended
                        const clonedEmployeeItem = $employeeItem.clone();
                        $('#' + cleanedFirstPositionName).append(clonedEmployeeItem);
                        // Append the reverse button to the cloned employee item
                        const reverseButton = $(
                            '<div><button class="btn btn-danger btn-sm reverse-btn">Reverse</button></div>');
                        clonedEmployeeItem.append(reverseButton);

                    } else {
                        console.log(`Element with ID '${cleanedFirstPositionName}' does not exist.`);
                    }

                    //step............. delete the employee list group item from the employees list................

                    $employeeItem.remove();

                }

            });

        }

        // Delegated event handler for reverse button click
        $(document).on('click', '.reverse-btn', function() {
            // Find the closest parent list-group-item
            const $employeeItem = $(this).closest('.list-group-item');
            const selectedEmployeeId = $($employeeItem).closest('.list-group-item').attr(
                'id');

            // Find the original div by ID
            const originalElement = document.getElementById("all-employees");
            if (originalElement) {
                //remove the reverse button
                $employeeItem.find('.reverse-btn').remove();
                // Reset input field values
                $employeeItem.find('input').val('');
                // Move the employee item back to the original div
                $employeeItem.appendTo(originalElement);
                //Reinitialize the clockpicker by callback
                const timeInId = "#time_in_" + selectedEmployeeId;
                console.log("the time in id is ", timeInId);
                const timeOutId = "#time_out_" + selectedEmployeeId;
                console.log("the time out id is ", timeOutId);

                initializeClockpicker(timeOutId);
                initializeClockpicker(timeInId);
                console.log('Element moved back to the original div');
            } else {
                console.log(`Element with ID does not exist.`);
            }

        });
    </script>


    <script>
        //to make sortable and drag and drop
        $(document).ready(function() {
            var all_employees = document.getElementById('all-employees');
            // var present_employees = document.getElementById('present-employees');

            const all_employees_object = new Sortable(all_employees, {
                group: 'shared',
                animation: 150,

                onAdd: function(evt) {
                    const sourceElement = evt.from;
                    console.log("from ", sourceElement);
                    const draggedElement = evt.item; // Dragged item
                    console.log("item ", draggedElement);
                    const targetElement = evt.to; // Target list
                    console.log("target ", targetElement);

                    //access buttons of the dragged element
                    const draggedButtons = $(draggedElement).find('.position-btn');
                    const targetId = $(targetElement).attr('id');

                    //accessing employee id
                    selectedEmployeeId = $(draggedElement).closest('.list-group-item').attr(
                        'id');

                    //accessing employee start time
                    const startTime = $('[name="time_in_' + selectedEmployeeId + '"]')
                        .val();

                    //accessing employee end time
                    const endTime = $('[name="time_out_' + selectedEmployeeId + '"]').val();

                    //check if vales are empty
                    let haveValues = true;
                    if (startTime == "" || endTime == "") {
                        haveValues = false;
                    }

                    if (haveValues) {
                        //remove the reverse button
                        $(draggedElement).find('.reverse-btn').remove();
                        // Reset input field values
                        $(draggedElement).find('input').val('');
                        //Reinitialize the clockpicker for current element's both inputs
                        const timeInId = "#time_in_" + selectedEmployeeId;
                        initializeClockpicker(timeInId);
                        const timeOutId = "#time_out_" + selectedEmployeeId;
                        initializeClockpicker(timeOutId);
                    }

                },

            });



            // Create Sortable objects for each card's div with class "sortable-card-body"
            $('.sortable-card-body').each(function() {
                const cardSortable = new Sortable(this, {
                    group: 'shared',
                    animation: 150,

                    onStart: function(evt) {
                        const sourceElement = evt.from; // Source list
                        const draggedElement = evt.item; // Dragged item

                        // Find buttons within dragged element
                        const buttons = $(draggedElement).find('.position-btn');

                        //if employee has only one position button, prevent pull out
                        if (buttons.length <= 1) {
                            evt.preventDefault();
                        }
                    },

                    onAdd: function(evt) {
                        const draggedElement = evt.item; // Dragged item
                        const targetElement = evt.to; // Target list

                        //access buttons of the dragged element
                        const draggedButtons = $(draggedElement).find('.position-btn');
                        const targetId = $(targetElement).attr('id');

                        //accessing employee id
                        selectedEmployeeId = $(draggedElement).closest('.list-group-item').attr(
                            'id');

                        //accessing employee start time
                        const startTime = $('[name="time_in_' + selectedEmployeeId + '"]')
                            .val();

                        //accessing employee end time
                        const endTime = $('[name="time_out_' + selectedEmployeeId + '"]').val();

                        //check if vales are empty
                        let haveValues = true;
                        if (startTime == "" || endTime == "") {
                            haveValues = false;
                        }

                        // Check if any of the dragged buttons' text (without spaces) matches the target's ID
                        let canDrop = false;
                        draggedButtons.each(function() {
                            const buttonText = $(this).text().replace(/\s+/g, '');
                            if (buttonText === targetId) {
                                canDrop = true;
                                return false; // Exit the loop early
                            }
                        });

                        if (canDrop == false || haveValues == false) {
                            // If the condition is not met, prevent dropping
                            // Return the dragged element to its original list
                            evt.from.appendChild(
                                draggedElement
                            );
                        }
                    },

                });
            });


        });
    </script>

    <script>
        $(document).ready(function() {

            function sendEmployeeData() {
                // Select all the card body divs
                var cardBodies = $('.sortable-card-body');

                // Array to store employee data
                var employeeData = [];

                // Loop through each card body
                cardBodies.each(function() {
                    var cardBody = this;
                    var cardBodyId = $(cardBody).attr('id').replace(/([a-z])([A-Z])/g, '$1 $2');

                    // Loop through each employee item within the card body
                    $(cardBody).find('.list-group-item').each(function() {

                        selectedEmployeeId = $(this).closest('.list-group-item').attr('id');
                        //console.log(selectedEmployeeId);

                        var startTime = $('[name="time_in_' + selectedEmployeeId + '"]').val();
                        //console.log(startTime);

                        var endTime = $('[name="time_out_' + selectedEmployeeId + '"]').val();
                        //console.log(endTime);

                        var employeeName = $(this).closest('.list-group-item').find(
                            '[data-employee-name]').data('employee-name');
                        var employeeAge = $(this).closest('.list-group-item').find(
                            '[data-employee-age]').data('employee-age');

                        var positions = [];
                        $(this).find('.position-btn').each(function() {
                            positions.push($(this).text().trim());
                        });

                        var employeeItemData = {
                            employeeId: selectedEmployeeId,
                            startTime: startTime,
                            endTime: endTime,
                            positions: positions,
                            cardBodyId: cardBodyId,
                            employeeName: employeeName,
                            employeeAge: employeeAge
                        };

                        employeeData.push(employeeItemData);
                    });
                });

                // Log the employee data to the console before ajax
                console.log(employeeData);

                $.ajax({
                    type: 'POST',
                    url: '{{ route('employeeSchedule') }}',
                    data: JSON.stringify(employeeData),
                    contentType: 'application/json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {

                        console.log("AJAX request successful");
                        console.log("Message:", response.message);

                        // Access the processed employee data
                        const processedData = response.processedData;
                        const schedule = response.schedule;
                        const breakArray = response.breakArray;
                        const waitingQue = response.waitingQue;

                        console.log("Processed Data:", processedData);
                        console.log("Schedule:", schedule);
                        console.log("breakArray:", breakArray);
                        console.log("waitingQue:", waitingQue);

                        // Call the showSchedule function to populate the schedule
                        showSchedule(response.schedule);

                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX error:', error);
                        console.log('XHR:', xhr);
                    }
                });
            }

            $('#fetchScheduleButton').click(function() {
                sendEmployeeData();
            });

            function showSchedule(scheduleData) {
                // Clear the existing content of the schedule table
                $('#schedule-table').empty();

                // Iterate through the schedule data and populate the table
                scheduleData.forEach(function(rowData, rowIndex) {
                    var rowHtml = '';

                    // The first row contains column headers
                    if (rowIndex === 0) {
                        rowHtml = '<thead><tr>';
                    } else {
                        rowHtml = '<tr>';
                    }

                    rowData.forEach(function(cellData, columnIndex) {
                        // Replace null cell data with an empty space
                        if (cellData === null) {
                            cellData = '';
                        }
                        if (rowIndex === 0) {
                            // The first cell of the first row is an empty header
                            if (columnIndex === 0) {
                                rowHtml += '<th>' + cellData + '</th>';
                            } else {
                                rowHtml += '<th>' + cellData + '</th>';
                            }
                        } else {
                            if (columnIndex === 0) {
                                // The first cell of other rows contains row headers
                                rowHtml += '<th>' + cellData + '</th>';
                            } else {
                                rowHtml += '<td>' + cellData + '</td>';
                            }
                        }
                    });

                    rowHtml += '</tr>';

                    if (rowIndex === 0) {
                        rowHtml += '</thead><tbody>';
                    }

                    $('#schedule-table').append(rowHtml);
                });

                $('#schedule-table').append('</tbody>');
            }


        });
    </script>


@endsection


@if (session('success'))
    <x-alert type="success">{{ session('success') }}</x-alert>
@elseif(session('error'))
    <x-alert type="error">{{ session('error') }}</x-alert>
@elseif(session('warning'))
    <x-alert type="warning">{{ session('warning') }}</x-alert>
@endif


<div class="row">
    <div class="col-7">

        @foreach ($positions as $position)
            <div class="card mb-2">
                <div class="card-header d-flex align-items-center disabled">
                    <div>
                        <p class="mb-1"> {{ $position->name }} </p>
                    </div>
                </div>
                <div class="card-body sortable-card-body" id="{{ str_replace(' ', '', $position->name) }}">

                </div>
            </div>
        @endforeach

    </div>

    <div class="col-5">
        <div class="chat-block">
            <div class="chat-sidebar" style="width: 100%;">
                <div tabindex="1" class="chat-sidebar-content" {{-- style="overflow: hidden; outline: none;" --}}>
                    <div id="pills-tabContent" class="tab-content">
                        <div id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab"
                            class="tab-pane fade active show">

                            <div class="card-header d-flex align-items-center disabled" id="drop-employees">
                                <div>
                                    <p class="mb-1"> All Employees </p>
                                </div>
                            </div>

                            <div class="list-group list-group-flush" id="all-employees"
                                style="border: solid #eb2f516b; min-height:100px;">


                                @foreach ($employees as $employee)
                                    <a href="#" class="list-group-item d-flex align-items-center"
                                        id="{{ $employee->id }}">
                                        <div class="pe-3">
                                            <div class="avatar avatar-info avatar-state-secondary">
                                                <span class="avatar-text rounded-circle"> {{ $employee->id }}
                                                </span>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="mb-1" data-employee-name="{{ $employee->name }}"
                                                data-employee-age="{{ $employee->age }}">
                                                {{ $employee->name }} ({{ $employee->age }})</p>
                                            <div class="text-muted d-flex align-items-center">
                                                <input type="text" class="form-control clockpicker-example"
                                                    id="time_in_{{ $employee->id }}"
                                                    name="time_in_{{ $employee->id }}" placeholder="Start Time">
                                                <input type="text" class="form-control clockpicker-example"
                                                    id="time_out_{{ $employee->id }}"
                                                    name="time_out_{{ $employee->id }}" placeholder="End Time">
                                                <!-- hamza -->
                                            </div>

                                            <div class="mt-2">
                                                @foreach ($employee->positions as $position)
                                                    <button type="button"
                                                        class="btn btn-primary btn-small position-btn"
                                                        data-position-name="{{ $position->name }}">{{ $position->name }}</button>
                                                @endforeach()
                                            </div>
                                        </div>
                                    </a>
                                @endforeach()
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="row my-3">
    <div class="col">
        <button id="fetchScheduleButton" class="btn btn-primary">Fetch Schedule</button>

    </div>
</div>

<div class="row">
    <div class="col">
        <div class="table-responsive" tabindex="1" style="overflow: hidden; outline: none;">
            <table id="schedule-table" class="table table-custom table-sm">
                <!-- Table will be populated dynamically using JavaScript -->
            </table>
        </div>
    </div>
</div>

@endsection
