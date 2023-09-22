@extends('layouts.app')

@section('title', __('Add New Employee'))
@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-clockpicker.min.css') }}" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('bundlingScripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
                    $('.multiple-positions-selection').select2({
                        placeholder: "Click to select positions"
                    });

                    // Add event listener to the select box
                    $('.multiple-positions-selection').on('select2:opening', function(e) {
                        // Check if age input is empty
                        if (!$('input[name="age"]').val()) {
                            e.preventDefault(); // Prevent the select2 from opening
                            // Show SweetAlert alert
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'You cannot select a position if age is not set!',
                            });
                        }
                    });

                    // Add event listener to the submit button
                    $('form').on('submit', function(e) {
                            // Check if name, age, and at least one position is selected
                            if (!$('input[name="name"]').val() || !$('input[name="age"]').val() || $(
                                    'select[name="positions[]"]').val().length === 0 ){
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
    <div class="content ">

        <div class="row flex-column-reverse flex-md-row">
            <div class="col-md-8">
                <div class="tab-content" id="myTabContent">
                    <div id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <div class="mb-4">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h6 class="card-title mb-4">Basic Information</h6>
                                    <form method="post" action="{{ route('users.store') }}">
                                        @csrf
                                        <div class="row">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Name</label>
                                                        <input type="text" class="form-control" name="name">
                                                    </div>

                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Age</label>
                                                        <input type="number" name="age" class="form-control">
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="row">

                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <select class="multiple-positions-selection" name="positions[]"
                                                            multiple="multiple">
                                                            @foreach ($positions as $position)
                                                                <option value="{{ $position->id }}">{{ $position->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <button type="submit" class="btn btn-primary">Save</button>
                                                </div>
                                            </div>

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
