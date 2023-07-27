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
    // hamza
    $('.clockpicker-example').clockpicker({
        donetext: 'Done',
        afterDone: function() {
            console.log("after done");
        }

    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

<script>
$(document).ready(function() {
    var all_employees = document.getElementById('all-employees');
    var present_employees = document.getElementById('present-employees');
    var positions = document.getElementById('positions');
    var positions2 = document.getElementById('positions2');

    const all_employees_object = new Sortable(all_employees, {
        group: 'shared',
        animation: 150,
        onStart: function(evt) {
            console.log("Sorting started. Element:", evt.item);
        },
        onEnd: function(evt) {
            console.log("Sorting ended. Element:", evt.item);
        },
        onAdd: function(evt) {
            console.log("Item added to list. Element:", evt.item);
        },
        onUpdate: function(evt) {
            console.log("Item updated within the list. Element:", evt.item);
        },
        onRemove: function(evt) {
            console.log("Item removed from list. Element:", evt.item);
        },
    });

    const present_employees_object = new Sortable(present_employees, {
        group: 'shared',
        animation: 150,
        onEnd: function(evt) {
            console.log(evt.item);


            const nameElement = evt.item.querySelector(".mb-1");

            // Extract the name (textContent) from the <p> element
            const name = nameElement.textContent.trim();

            console.log("Name:", name);

            // Optional: Remove the original item from the first list (comment this line if you want to keep the original)
            // evt.from.removeChild(evt.item);
        },
    });

    const positions_object = new Sortable(positions, {
        group: 'shared',
        animation: 150,


    });

    const positions_object2 = new Sortable(positions2, {
        group: 'shared',
        animation: 150,

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

<positions></positions>

@endsection
