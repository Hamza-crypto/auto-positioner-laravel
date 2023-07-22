@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

@section('scripts')

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

@if(session('success'))
<x-alert type="success">{{ session('success') }}</x-alert>
@elseif(session('error'))
<x-alert type="error">{{ session('error') }}</x-alert>
@elseif(session('warning'))
<x-alert type="warning">{{ session('warning') }}</x-alert>
@endif

<div class="row">
    <div class="col-9">
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

    </div>
</div>


@endsection
