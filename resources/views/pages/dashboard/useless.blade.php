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

@section('scripts')
    <script src="{{ asset('assets/js/bootstrap-clockpicker.min.js') }}"></script>
    {{-- <script>
        $(document).ready(function() {
            $('#schedule-table').DataTable();
        });
    </script> --}}

    <script>
        $(document).ready(function() {
            $('.clockpicker-example').clockpicker({
                donetext: 'Done'
            });

            var selectedEmployeeId = null;


            //step1...............accessing the data after the time is set.................


            $('.clockpicker-example').on('change', function() {

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

                if (startTime && endTime) {

                    const firstPositionName = $employeeItem.find('[data-position-name]:first').data(
                        'position-name');
                    console.log("first position of this employee is: " + firstPositionName);
                    // Remove spaces from firstPositionName
                    const cleanedFirstPositionName = firstPositionName.replace(/\s+/g, '');
                    const matchingElement = document.getElementById(cleanedFirstPositionName);
                    if (matchingElement) {
                        console.log(`Element with ID '${cleanedFirstPositionName}' exists.`);
                        //$('#' + cleanedFirstPositionName).append($employeeItem);

                        const clonedEmployeeItem = $employeeItem.clone();
                        $('#' + cleanedFirstPositionName).append(clonedEmployeeItem);
                        // Append the reverse button to the cloned employee item
                        const reverseButton = $(
                            '<button class="btn btn-danger btn-sm reverse-btn">Reverse</button>');
                        clonedEmployeeItem.append(reverseButton);

                        console.log('');
                        console.log('element dropped successfully ');
                        console.log('');
                        console.log(clonedEmployeeItem);

                    } else {
                        console.log(`Element with ID '${cleanedFirstPositionName}' does not exist.`);
                    }



                    // Access employee's name using data-employee-name attribute
                    const employeeName = $(this).closest('.list-group-item').find('[data-employee-name]')
                        .data('employee-name');
                    console.log("Employee Name:", employeeName);

                    // Access the age using data-employee-age attribute
                    const employeeAge = $(this).closest('.list-group-item').find('[data-employee-age]')
                        .data('employee-age');
                    console.log("Employee Age:", employeeAge);

                    //accessing employee positions
                    const $positionButtons = $(this).closest('.list-group-item').find(
                        '[data-position-name]');
                    $positionButtons.each(function() {
                        const positionName = $(this).data('position-name');
                        console.log("Position Name:", positionName);
                    });
                    console.log("both values achieved now call a function");


                    //step2.........................creating new element................................. 


                    const $newElement = $(
                        '<a href="#" class="list-group-item d-flex align-items-center">' +
                        '<div class="pe-3">' +
                        '<div class="avatar avatar-info avatar-state-secondary">' +
                        '<span class="avatar-text rounded-circle">' + selectedEmployeeId + '</span>' +
                        '</div>' +
                        '</div>' +
                        '<div>' +
                        '<p class="mb-1" data-employee-name="' + employeeName +
                        '" data-employee-age="' +
                        employeeAge + '" >' +
                        employeeName + ' (' + employeeAge + ')</p>' +
                        '<div class="text-muted d-flex align-items-center">' +
                        '<input type="text" class="form-control clockpicker-example" name="time_in_' +
                        selectedEmployeeId + '" readonly value="' +
                        startTime + '">' +
                        '<input type="text" class="form-control clockpicker-example" name="time_out_' +
                        selectedEmployeeId + '" readonly value="' +
                        endTime + '">' +
                        '</div>' +
                        '<div class="mt-2">' +
                        $employeeItem.find('[data-position-name]').map(function() {
                            const positionName = $(this).data('position-name');
                            return '<button type="button" class="btn btn-primary btn-small">' +
                                positionName + '</button>';
                        }).get().join(' ') +
                        '</div>' +
                        '</div>' +
                        '</a>'
                    );


                    //step3...................... get the first position of the current employee here..........................


                    // const firstPositionName = $employeeItem.find('[data-position-name]:first').data(
                    //     'position-name');
                    // console.log("first position of this employee is: " + firstPositionName);

                    // // Remove spaces from firstPositionName
                    // const cleanedFirstPositionName = firstPositionName.replace(/\s+/g, '');


                    //step4.............. Check if an element with the ID exists then append the current employee....


                    //const matchingElement = document.getElementById(cleanedFirstPositionName);
                    if (matchingElement) {
                        console.log(`Element with ID '${cleanedFirstPositionName}' exists.`);
                        //$('#' + cleanedFirstPositionName).append($newElement);
                    } else {
                        console.log(`Element with ID '${cleanedFirstPositionName}' does not exist.`);
                    }


                    //step............. delete the employee list group item from the employees list................


                    $employeeItem.remove();

                }

            });

            // Delegated event handler for reverse button click
            $(document).on('click', '.reverse-btn', function() {
                // Find the closest parent list-group-item
                const $employeeItem = $(this).closest('.list-group-item');

                // Find the original div by ID
                const originalElement = document.getElementById("all-employees");
                if (originalElement) {

                    $employeeItem.find('.reverse-btn').remove();
                    // Reset input field values
                    $employeeItem.find('input').val('');


                    // Move the employee item back to the original div
                    $employeeItem.appendTo(originalElement);
                    $('.clockpicker-example').clockpicker({
                        donetext: 'Done'
                    });
                    console.log('Element moved back to the original div');

                } else {
                    console.log(`Element with ID does not exist.`);
                }

            });

        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

    <script>
        //to make sortable and drag and drop
        $(document).ready(function() {
            var all_employees = document.getElementById('all-employees');
            // var present_employees = document.getElementById('present-employees');

            const all_employees_object = new Sortable(all_employees, {
                group: 'shared',
                animation: 150,

            });



            // Create Sortable objects for each card's div with class "sortable-card-body"
            $('.sortable-card-body').each(function() {
                const cardSortable = new Sortable(this, {
                    group: 'shared', // Set your desired group here
                    animation: 150,

                    onStart: function(evt) {
                        const sourceElement = evt.from; // Source list
                        const draggedElement = evt.item; // Dragged item
                        console.log('Drag started from:', sourceElement);
                        console.log('Dragged element:', draggedElement);

                        // Find buttons within dragged element
                        const buttons = $(draggedElement).find('.btn');

                        //if employee has only one position button, prevent pull out
                        if (buttons.length <= 1) {
                            evt.preventDefault();
                        }
                    },

                    onAdd: function(evt) {
                        const draggedElement = evt.item; // Dragged item
                        const targetElement = evt.to; // Target list

                        const draggedButtons = $(draggedElement).find('.btn');
                        const targetId = $(targetElement).attr('id');

                        // Check if any of the dragged buttons' text (without spaces) matches the target's ID
                        let canDrop = false;
                        draggedButtons.each(function() {
                            const buttonText = $(this).text().replace(/\s+/g, '');
                            if (buttonText === targetId) {
                                canDrop = true;
                                return false; // Exit the loop early
                            }
                        });

                        if (!canDrop) {
                            // If the condition is not met, prevent dropping
                            evt.from.appendChild(
                                draggedElement
                            ); // Return the dragged element to its original list
                        } else {
                            console.log('Dropped element:', draggedElement);
                            console.log('Dropped into:', targetElement);
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
                        var employeeId = $(this).find('.avatar-text').text();
                        var startTime = $(this).find('[name^="time_in_' + employeeId + '"]')
                            .val();
                        var endTime = $(this).find('[name^="time_out_' + employeeId + '"]').val();
                        var employeeName = $(this).closest('.list-group-item').find(
                            '[data-employee-name]').data('employee-name');
                        var employeeAge = $(this).closest('.list-group-item').find(
                            '[data-employee-age]').data('employee-age');

                        var positions = [];
                        $(this).find('.btn').each(function() {
                            positions.push($(this).text().trim());
                        });

                        var employeeItemData = {
                            employeeId: employeeId,
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

                // Log the employee data to the console
                console.log(employeeData);

                $.ajax({
                    type: 'POST',
                    url: '{{ route('employeeSchedule') }}',
                    data: JSON.stringify(employeeData), // Send as JSON string
                    contentType: 'application/json', // Set content type
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

        <div class="card mb-2">
            <div class="card-header d-flex align-items-center disabled">
                <div>
                    <p class="mb-1"> Register </p>
                </div>
            </div>
            <div class="card-body sortable-card-body" id="Register">

            </div>
        </div>

        <div class="card mb-2">
            <div class="card-header d-flex align-items-center disabled">
                <div>
                    <p class="mb-1"> Runner </p>
                </div>
            </div>
            <div class="card-body sortable-card-body" id="Runner">

            </div>
        </div>

        <div class="card mb-2">
            <div class="card-header d-flex align-items-center disabled">
                <div>
                    <p class="mb-1"> Fryer </p>
                </div>
            </div>
            <div class="card-body sortable-card-body" id="Fryer">

            </div>
        </div>

        <div class="card mb-2">
            <div class="card-header d-flex align-items-center disabled">
                <div>
                    <p class="mb-1"> Griller </p>
                </div>
            </div>
            <div class="card-body sortable-card-body" id="Griller">

            </div>
        </div>

        <div class="card mb-2">
            <div class="card-header d-flex align-items-center disabled">
                <div>
                    <p class="mb-1"> Mobile Order </p>
                </div>
            </div>
            <div class="card-body sortable-card-body" id="MobileOrder">


            </div>
        </div>

        <div class="card mb-2">
            <div class="card-header d-flex align-items-center disabled">
                <div>
                    <p class="mb-1"> BacklineCook </p>
                </div>
            </div>
            <div class="card-body sortable-card-body" id="BacklineCook">

            </div>
        </div>

        <div class="card mb-2">
            <div class="card-header d-flex align-items-center disabled">
                <div>
                    <p class="mb-1"> Sandwich Designer </p>
                </div>
            </div>
            <div class="card-body sortable-card-body" id="SandwichDesigner">

            </div>
        </div>
    </div>

    <div class="col-5">

        <div class="chat-block">

            <div class="chat-sidebar" style="width: 100%;">
                <div tabindex="1" class="chat-sidebar-content" {{-- style="overflow: hidden; outline: none;" --}}>

                    <div id="pills-tabContent" class="tab-content">
                        <div id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab"
                            class="tab-pane fade active show">
                            <div class="list-group list-group-flush" id="all-employees"
                                style="border: solid #eb2f516b; min-height:100px;">

                                <a href="#" class="list-group-item d-flex align-items-center disabled"
                                    id="drop-employees">
                                    <div>
                                        <p class="mb-1"> All Employees </p>
                                    </div>
                                </a>


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
                                                    name="time_in_{{ $employee->id }}" placeholder="Start Time">
                                                <input type="text" class="form-control clockpicker-example"
                                                    name="time_out_{{ $employee->id }}" placeholder="End Time">
                                                <!-- hamza -->
                                            </div>

                                            <div class="mt-2">

                                                @foreach ($employee->positions as $position)
                                                    <button type="button" class="btn btn-primary btn-small"
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
