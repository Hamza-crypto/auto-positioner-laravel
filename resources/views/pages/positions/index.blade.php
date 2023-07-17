@extends('layouts.app')

@section('title', 'All Positions')

@section('scripts')
<script>
$(document).ready(function() {
    $('#users-table').DataTable();
});

function confirmDelete(event, formElement) {
    event.preventDefault(); // Prevent the form submission initially
    const btnId = formElement.id;

    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById("deleteForm_" + btnId).submit();
            Swal.fire(
                'Deleted!',
                'Your employee has been deleted.',
                'success'
            )
        }
    })

}
console.log($('#users-table'));
</script>
@endsection

@section('content')
<div class="content ">
    <div class="table-responsive" tabindex="1" style="overflow: hidden; outline: none;">
        <table id="users-table" class="table table-custom table-lg">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Count</th>
                </tr>
            </thead>
            <tbody>
                @foreach($positions as $position)
                <tr>

                    <td> {{ $position->name }} </td>
                    <td> {{ $position->count }} </td>

                </tr>
                @endforeach

            </tbody>
        </table>
    </div>

</div>
@endsection
