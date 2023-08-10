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

<script>
$(document).ready(function() {

    // Event listener for changes in "time_out" inputs
    $(document).ready(function() {
        $("#all-employees").on("input", ".list-group-item input[name='time_out']", function() {
            // Get the closest "list-group-item" parent
            const $listItem = $(this).closest(".list-group-item");
            alert('asd');

            // Get the data attributes (id, name, positions) of the list item
            const id = $listItem.find(".avatar-text").text().trim();
            const name = $listItem.find(".mb-1").text().trim();
            const positions = $listItem.find(".text-muted").text().trim();

            // Get the values of "time_in" and "time_out" inputs
            const timeIn = $listItem.find("input[name='time_in']").val();
            const timeOut = $(this)
                .val(); // The value of the "time_out" input that triggered the event

            // Your custom callback function
            yourCallbackFunction(id, name, positions, timeIn, timeOut);
        });
    });

    // Custom callback function to be executed
    function yourCallbackFunction(id, name, positions, timeIn, timeOut) {
        // Example: Log the data to the console
        console.log("ID:", id);
        console.log("Name:", name);
        console.log("Positions:", positions);
        console.log("Time In:", timeIn);
        console.log("Time Out:", timeOut);
    }
    // hamza
    $('.clockpicker-example').clockpicker({
        donetext: 'Done',
        afterDone: function() {
            console.log("after done");
            const $divElement = $("#positions");

            var name = 'Alice';
            var id = 5;



            const $newElement = $('<a href="#" class="list-group-item d-flex align-items-center">' +
                '<div class="flex-fill">' +
                '<h6 class="mb-1">' + name + '</h6>' +
                '<small class="text-muted">' + id + '</small>' +
                '</div>' +
                '</a>');

            // Step 3: Append the new element as a child of the div
            // $divElement.append($newElement);
        }

    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

<script>
$(document).ready(function() {
    var all_employees = document.getElementById('all-employees');
    // var present_employees = document.getElementById('present-employees');
    var positions = document.getElementById('positions');
    // var positions2 = document.getElementById('positions2');

    const all_employees_object = new Sortable(all_employees, {
        group: 'shared',
        animation: 150,

    });

    // const present_employees_object = new Sortable(present_employees, {
    //     group: 'shared',
    //     animation: 150,
    //     onEnd: function(evt) {
    //         console.log(evt.item);


    //         const nameElement = evt.item.querySelector(".mb-1");

    //         // Extract the name (textContent) from the <p> element
    //         const name = nameElement.textContent.trim();

    //         console.log("Name:", name);

    //         // Optional: Remove the original item from the first list (comment this line if you want to keep the original)
    //         // evt.from.removeChild(evt.item);
    //     },
    // });

    const positions_object = new Sortable(positions, {
        group: 'shared',
        animation: 150,
    });

    // const positions_object2 = new Sortable(positions2, {
    //     group: 'shared',
    //     animation: 150,

    // });


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
    setInterval(updateTable, 150000); // 3000 milliseconds = 3 seconds

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
    <div class="col-7">
        <div class="card mb-3">
            <div class="card-body" id="positions">
                <a href="#" class="list-group-item d-flex align-items-center disabled fixed-item">
                    <div>
                        <p class="mb-1"> Fryer </p>
                    </div>
                </a>

               

            </div>
        </div>

        <div class="card">
            <div class="card-body" id="positions2">
                <a href="#" class="list-group-item d-flex align-items-center disabled">
                    <div>
                        <p class="mb-1"> Fryer </p>
                    </div>
                </a>


            </div>
        </div>
    </div>
    <!-- <div class="col-6">
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
    </div> -->

    <!-- <div class="col-3">

        <div class="chat-block">

            <div class="chat-sidebar">


                <div tabindex="1" class="chat-sidebar-content" style="overflow: hidden; outline: none;">

                    <div id="pills-tabContent" class="tab-content">
                        <div id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab"
                            class="tab-pane fade active show">
                            <div class="list-group list-group-flush" id="present-employees"
                                style="border: solid #eb2f516b; min-height:100px;">

                                <a href="#" class="list-group-item d-flex align-items-center disabled"
                                    id="drop-employees">
                                    <div>
                                        <p class="mb-1"> Present Employees </p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->

    <div class="col-5">

        <div class="chat-block">

            <div class="chat-sidebar" style="width: 100%;">

                <div tabindex="1" class="chat-sidebar-content" style="overflow: hidden; outline: none;">

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
                                            <input type="text" class="form-control clockpicker-example" name="time_in"
                                                placeholder="Start Time">
                                            <input type="text" class="form-control clockpicker-example" name="time_out"
                                                placeholder="End Time">
                                            <!-- hamza -->
                                        </div>

                                        <div class="mt-2">

                                            @foreach($employee->positions as $position)
                                            <button type="button"
                                                class="btn btn-primary btn-small">{{ $position->name }}</button>
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

@endsection
