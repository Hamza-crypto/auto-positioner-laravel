@extends('layouts.app')

@section('title', 'All Employees')

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
</script>
<!-- <script src="{{ asset('assets/js/users.js') }}"></script> -->
@endsection

@section('content')
<div class="content ">


    <div class="table-responsive" tabindex="1" style="overflow: hidden; outline: none;">
        <table id="users-table" class="table table-custom table-lg">
            <thead>
                <tr>
                    {{--                    <th>--}}
                    {{--                        <input class="form-check-input select-all" type="checkbox" data-select-all-target="#users" id="defaultCheck1">--}}
                    {{--                    </th>--}}
                    <th>Name</th>
                    <th>Age</th>
                    <th>Positions</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    {{--                        <td>--}}
                    {{--                            <input class="form-check-input" type="checkbox">--}}
                    {{--                        </td>--}}
                    <td>
                        <a href="{{ route('users.edit', $user->id) }}">
                            {{ $user->name }}
                        </a>
                    </td>
                    <td>{{ $user->age }}</td>
                    <td>
                        @foreach($user->positions as $position)
                        <span class="badge bg-success">{{ $position['name'] }}</span>
                        @endforeach
                    </td>

                    <td>
                        <div>
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary">Edit</a>

                            <form method="post" id="deleteForm_{{$user->id}}"
                                action="{{ route('users.destroy', $user->id) }}" style="display: inline"
                                onsubmit="return customSubmit()">
                                @csrf
                                @method('DELETE')

                                <button class="btn btn-danger" id="{{$user->id}}" onclick="confirmDelete(event, this)">
                                    Delete
                                </button>

                            </form>

                        </div>

                    </td>
                </tr>
                @endforeach

            </tbody>
        </table>
    </div>

</div>
@endsection