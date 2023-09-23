@extends('layouts.app')

@section('title', 'Edit Profile')

{{-- @section('styles')
@endsection --}}

@section('scripts')
    <script>
        $(document).ready(function() {

            // $('.multiple-positions-selection').select2({
            // placeholder: "Click to select positions"
            // });

            // Add event listener to the select box
            // $('.multiple-positions-selection').on('select2:opening', function(e) {
            // // Check if age input is empty
            // if (!$('input[name="age"]').val()) {
            // e.preventDefault(); // Prevent the select2 from opening
            // // Show SweetAlert alert
            // Swal.fire({
            // icon: 'error',
            // title: 'Oops...',
            // text: 'You cannot select a position if age is not set!',
            // });
            // }
            // });

            // Add event listener to the submit button
            $('form').on('submit', function(e) {

                if (!$('input[name="name"]').val() || !$('input[name="age"]').val() ||
                    $("input[name='positions[]']:checked").length === 0) {
                    e.preventDefault(); // Prevent form submission
                    // Show SweetAlert alert
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Please fill in name, age, and select at least one position!',
                    });
                }
            });
        });
    </script>
@endsection

@section('content')
    <h1 class="h3 mb-3">Profile</h1>

    @if (session('success'))
        <x-alert type="success">{{ session('success') }}</x-alert>
    @elseif(session('account'))
        <x-alert type="success">{{ session('account') }}</x-alert>
    @endif

    <div class="content ">

        <div class="row flex-column-reverse flex-md-row">
            <div class="col-md-8">
                <div class="tab-content" id="myTabContent">
                    <div id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <div class="mb-4">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h6 class="card-title mb-4">Basic Information</h6>
                                    <form method="post" action="{{ route('users.update', $user->id) }}">
                                        @csrf
                                        @method('PATCH')
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Name</label>
                                                    <input type="text" class="form-control" name="name"
                                                        value="{{ $user->name }}">
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Positions</label>


                                                    @foreach ($positions as $position)
                                                        <div class="form-check">
                                                            @if (in_array($position->id, $user_positions))
                                                                <input class="form-check-input" type="checkbox"
                                                                    name="positions[]" value="{{ $position->id }}"
                                                                    id="checkBox{{ $position->id }}" checked>
                                                                <label class="form-check-label"
                                                                    for="checkBox{{ $position->id }}">
                                                                    {{ $position->name }}
                                                                </label>
                                                            @else
                                                                <input class="form-check-input" type="checkbox"
                                                                    name="positions[]" value="{{ $position->id }}"
                                                                    id="checkBox{{ $position->id }}">
                                                                <label class="form-check-label"
                                                                    for="checkBox{{ $position->id }}">
                                                                    {{ $position->name }}
                                                                </label>
                                                            @endif
                                                            
                                                        </div>
                                                    @endforeach
                                                </div>

                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Age</label>
                                                    <input type="number" name="age" class="form-control"
                                                        value="{{ $user->age }}">
                                                </div>
                                            </div>

                                            <button type="submit" class="btn btn-primary">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
