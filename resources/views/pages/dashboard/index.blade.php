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
</style>
@endsection

@section('scripts')
<!-- <script src="https://cdn.jsdelivr.net/npm/@shopify/draggable@1.0.0-beta.11/lib/draggable.bundle.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

<script>
$(document).ready(function() {
    var employees = document.getElementById('all-employees');
    var positions = document.getElementById('present-employees');




    // var sortable = Sortable.create(el, {
    //     group: 'shared'
    // });

    // var el = document.getElementById('positions');
    // var sortable = Sortable.create(el, {
    //     group: 'shared'
    // });

    const employeesList = new Sortable(employees, {
        group: 'shared',
        animation: 150,
    });

    const positionsList = new Sortable(positions, {
        group: 'shared',
        animation: 150,
    });

    console.log(employeesList);
    employeesList.on('sort', (evt) => {
        const employeeId = evt.item.getAttribute('data-id');
        const currentPosition = evt.from;
        const newPosition = evt.to;

        // Check if the item is being moved from the employees list to a position list
        if (currentPosition.classList.contains('employees') && newPosition.classList.contains(
                'positions')) {
            // Your logic to add the employee to the position
            const positionId = newPosition.getAttribute('data-id');
            // ... Add the employee with the corresponding ID to the position using AJAX or other methods

            // Remove the placeholder if an employee is added to the position
            const placeholder = newPosition.querySelector('.placeholder');
            if (placeholder) {
                placeholder.remove();
            }

            // Optionally, you can remove the employee from the previous position if needed
            // ...

            // Perform any other necessary actions
            // ...
        }
    });

    positionsList.on('sort', (evt) => {
        const position = evt.item;
        const currentPosition = evt.from;
        const newPosition = evt.to;

        // Check if the item is being moved from a position list to the employees list (i.e., removed from a position)
        if (currentPosition.classList.contains('positions') && newPosition.classList.contains(
                'employees')) {
            // Your logic to remove the employee from the position
            const employeeId = position.getAttribute('data-id');
            // ... Remove the employee with the corresponding ID from the position using AJAX or other methods

            // Add a placeholder for the empty position
            const placeholder = document.createElement('div');
            placeholder.classList.add('placeholder');
            placeholder.textContent = 'Empty Position';
            newPosition.appendChild(placeholder);

            // Perform any other necessary actions
            // ...
        }
    });


});
</script>

<script>
$(document).ready(function() {

    // Function to send the AJAX request and update the table
    function updateTable() {
        $.get('/dashboard/table', function(response) {
            $('#table-body').html(response);
        });
    }

    // Initial AJAX request on page load
    updateTable();

    // Set interval to call the updateTable function every 3 seconds
    setInterval(updateTable, 1500); // 3000 milliseconds = 3 seconds

});
</script>


@endsection


@if(session('success'))
<x-alert type="success">{{ session('success') }}</x-alert>
@elseif(session('error'))
<x-alert type="error">{{ session('error') }}</x-alert>
@elseif(session('warning'))
<x-alert type="warning">{{ session('warning') }}</x-alert>
@endif

<div class="row">
    <div class="col-6">
        <table>
            <thead>
                <tr>
                    <th></th>
                    @foreach ($positions as $position)
                    <th>{{ $position->name }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody id="table-body">
            </tbody>
        </table>
    </div>

    <div class="col-3">

        <div class="chat-block">

            <div class="chat-sidebar">
                <h1>Present Employees </h1>

                <div tabindex="1" class="chat-sidebar-content" style="overflow: hidden; outline: none;">

                    <div id="pills-tabContent" class="tab-content">
                        <div id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab"
                            class="tab-pane fade active show">
                            <div class="list-group list-group-flush" id="present-employees"
                                style="border: solid black; min-height:100px;">

                                <a href="#" class="list-group-item d-flex align-items-center" id="drop-employees">

                                    <div>
                                        <p class="mb-1"> Drop employees here </p>
                                    </div>

                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-3">

        <div class="chat-block">

            <div class="chat-sidebar">
                <h1>All Employees </h1>
                <div tabindex="1" class="chat-sidebar-content" style="overflow: hidden; outline: none;">

                    <div id="pills-tabContent" class="tab-content">
                        <div id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab"
                            class="tab-pane fade active show">
                            <div class="list-group list-group-flush" id="all-employees"
                                style="border: solid black; min-height:100px;">

                                @foreach($employees as $employee)

                                <a href="#" class="list-group-item d-flex align-items-center">
                                    <div class="pe-3">
                                        <div class="avatar avatar-info avatar-state-secondary">
                                            <span class="avatar-text rounded-circle"> {{ $employee->id }}
                                            </span>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="mb-1">{{ $employee->name }}</p>
                                        <div class="text-muted d-flex align-items-center">

                                            {{ $employee->time_in }} - {{ $employee->time_out }}
                                        </div>
                                    </div>
                                    <div class="text-end ms-auto">
                                        {{ $employee->positions[0]->name }}
                                    </div>
                                </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
