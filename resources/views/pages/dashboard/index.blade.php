@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

@section('scripts')
<!-- <script src="https://cdn.jsdelivr.net/npm/@shopify/draggable@1.0.0-beta.11/lib/draggable.bundle.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

<script>
$(document).ready(function() {
    var employees = document.getElementById('employees');
    var positions = document.getElementById('positions');




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
@endsection

@if(session('success'))
<x-alert type="success">{{ session('success') }}</x-alert>
@elseif(session('error'))
<x-alert type="error">{{ session('error') }}</x-alert>
@elseif(session('warning'))
<x-alert type="warning">{{ session('warning') }}</x-alert>
@endif

<div class="row">
    <div class="col-9">


        <div class="row g-4 mb-4" id="positions">
            <div class="col-md-2" data-id="position1">
                <div class="card bg-cyan text-white-90">
                    <div class="card-body d-flex align-items-center">
                        <i class="bi bi-box-seam display-7 me-3"></i>
                        <div>
                            <span>Runner 1</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2" data-id="position2">
                <div class="card bg-purple text-white-90">
                    <div class="card-body d-flex align-items-center">
                        <i class="bi bi-heart display-7 me-3"></i>
                        <div>

                            <span>Runner 2</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2" data-id="position3">
                <div class="card bg-teal text-white-90">
                    <div class="card-body d-flex align-items-center">
                        <i class="bi bi-wallet2 display-7 me-3"></i>
                        <div>

                            <span>Fryer</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="col-3">

        <div class="chat-block">
            <div class="chat-sidebar">
                <div tabindex="1" class="chat-sidebar-content" style="overflow: hidden; outline: none;">
                    <div id="pills-tabContent" class="tab-content">
                        <div id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab"
                            class="tab-pane fade active show">
                            <div class="list-group list-group-flush" id="employees">

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
                                    <div class="text-end ms-auto"><i class="bi bi-camera-video text-danger"></i></div>
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